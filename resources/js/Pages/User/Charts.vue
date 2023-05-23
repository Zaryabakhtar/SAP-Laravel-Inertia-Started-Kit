<template>
  <div>
    <div class="sidebar">
      <Sidebar />
    </div>
    <div class="content">
      <div class="chart-container">
        <div ref="chart1" class="chart"></div>
      </div>
      <div class="chart-container">
        <div ref="chart2" class="chart"></div>
      </div>
      <div class="chart-container">
        <div ref="chart3" class="chart"></div>
      </div>
    </div>
  </div>
</template>

<script>
import Sidebar from '../Shared/Sidebar.vue';
import * as echarts from 'echarts';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

export default {
  components: {
    Sidebar
  },
  data() {
    return {
      chartData: {
        question_1: {},
        question_2: {},
        question_3: {},
      },
      charts: {}, // Store references to the chart instances
    };
  },
  mounted() {
    this.setupPusher();
    this.fetchChartData();
    window.addEventListener('resize', this.handleWindowResize);
  },
  beforeUnmount() {
    this.destroyCharts();
    window.removeEventListener('resize', this.handleWindowResize);
  },
  methods: {
    setupPusher() {
      var echo = new Echo({
        broadcaster: 'pusher',
        key: 'ea434f0cdc68ccff572b',
        cluster: 'ap2',
        // Add any other Pusher options you need
      });

      echo.channel('chart-data-update').listen('ChartDataUpdated', (response) => {
        // Handle the received data
        this.chartData.question_1 = response.data.question_1;
        this.chartData.question_2 = response.data.question_2;
        this.chartData.question_3 = response.data.question_3;
        this.updateCharts();
      });
    },
    fetchChartData() {
      axios
        .get('/user/get-chart-data') // Replace with your Laravel route for retrieving chart data
        .then(response => {
          console.log(response.data);
          const { question_1, question_2, question_3 } = response.data;
          this.chartData.question_1 = question_1;
          this.chartData.question_2 = question_2;
          this.chartData.question_3 = question_3;
          this.updateCharts();
        });
    },
    createChart(containerId, chartData) {
      const chartContainer = this.$refs[containerId];
      if (!chartContainer) {
        // Exit the method if the chart container is not found
        return;
      }

      const chart = echarts.getInstanceByDom(chartContainer);
      if (chart) {
        chart.dispose(); // Dispose the existing chart instance
      }
      const newChart = echarts.init(chartContainer);
      this.charts[containerId] = newChart; // Store the reference to the chart instance

      const options = {
        title: {
          text: `Chart ${containerId}`,
          left: 'center',
        },
        tooltip: {
          trigger: 'item',
          formatter: '{b} ({d}%)',
        },
        series: [
          {
            name: 'Answers',
            type: 'pie',
            radius: '50%',
            data: Object.keys(chartData).map(key => ({
              name: key,
              value: chartData[key],
            })),
            label: {
              formatter: '{b} ({d}%)',
            },
          },
        ],
      };

      newChart.setOption(options);
    },
    updateCharts() {
      this.createChart('chart1', this.chartData.question_1);
      this.createChart('chart2', this.chartData.question_2);
      this.createChart('chart3', this.chartData.question_3);
    },
    destroyCharts() {
      for (const chartId in this.charts) {
        const chart = this.charts[chartId];
        chart.dispose();
      }
      this.charts = {};
    },
    handleWindowResize() {
      // Update chart dimensions on window resize event
      this.updateChartDimensions();
    },
    updateChartDimensions() {
      for (const chartId in this.charts) {
        const chart = this.charts[chartId];
        const chartContainer = this.$refs[chartId];
        if (chart && chartContainer) {
          const { offsetWidth, offsetHeight } = chartContainer;
          chart.resize({
            width: offsetWidth,
            height: offsetHeight,
          });
        }
      }
    },
  },
};
</script>

<style>
.sidebar {
  width: 20%;
}

.content {
  width: 80%;
}

.chart-container {
  display: inline-block;
  width: 100%;
  padding: 10px;
  box-sizing: border-box;
}

.chart {
  height: 400px;
  width: 100%; /* Set initial width to 100% */
}
</style>
