<template>
  <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white rounded shadow" id="quiz">
      <h1 class="text-3xl font-bold mb-4">Questionnaire</h1>

      <div class="mb-4">
        <progress class="w-full" :max="questions.length" :value="questionIndex + 1"></progress>
        <span class="text-sm">{{ questionIndex + 1 }} / {{ questions.length }}</span>
      </div>

      <div v-if="questionIndex < questions.length">
        <h2 class="text-xl font-bold mb-2">Question {{ questionIndex + 1 }}</h2>
        <p class="mb-4">{{ questions[questionIndex].question }}</p>

        <div v-for="option in questions[questionIndex].options" :key="option" class="mb-4 bg-gray-100 p-4 rounded shadow option" @dblclick="selectOptionAndNextQuestion(option)">
          <input type="radio" :name="'question_' + parseInt(questionIndex + 1)" :id="option" :value="option" v-model="selectedOption" class="mr-2">
          <label :for="option" class="text-gray-800">{{ option }}</label>
          <span v-if="answers.includes(option)" class="text-green-500 ml-2">
            <i class="fas fa-check"></i>
          </span>
        </div>
      </div>

      <div v-else>
        <h2 class="text-xl font-bold mb-2">Thank you for completing the questionnaire!</h2>
        <p class="mb-4">Your answers:</p>
        <ul class="mb-4">
          <li v-for="(answer, index) in answers" :key="index" class="text-gray-800">{{ answer }}</li>
        </ul>

        <h3 class="text-xl font-bold mb-2">Signup Form</h3>
        <form @submit.prevent="submitForm">
          <div class="mb-4">
            <label for="name" class="block text-gray-800">Name:</label>
            <input type="text" id="name" v-model="formData.name" required class="w-full px-3 py-2 border border-gray-300 rounded block focus:outline-none focus:ring-blue-500 focus:border-blue-500">
          </div>

          <div class="mb-4">
            <label for="email" class="block text-gray-800">Email:</label>
            <input type="email" id="email" v-model="formData.email" required class="w-full px-3 py-2 border border-gray-300 rounded block focus:outline-none focus:ring-blue-500 focus:border-blue-500">
          </div>

          <div class="mb-4">
            <label for="password" class="block text-gray-800">Password:</label>
            <input type="password" id="password" v-model="formData.password" required class="w-full px-3 py-2 border border-gray-300 rounded block focus:outline-none focus:ring-blue-500 focus:border-blue-500">
          </div>

          <Link method="post" as="button" type="button" href="/questionnaire/submit-answers" :data="{ userData: this.formData,answers: this.answers, }" class="w-full px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Submit Answers
          </Link>
        </form>
      </div>
    </div>
  </div>
</template>


<script>
import { useForm } from '@inertiajs/inertia-vue3';
import axios from 'axios';
import {Link} from '@inertiajs/inertia-vue3';

export default {
  props: {
    answers : Object
  },
  data() {
    return {
      questions: [
        {
          question: 'Question 1: Which option do you prefer?',
          options: ['a', 'b', 'c', 'd'],
        },
        {
          question: 'Question 2: Which number do you choose?',
          options: ['1', '2', '3', '4', '5'],
        },
        {
          question: 'Question 3: Which letter do you select?',
          options: ['u', 'v', 'w', 'x', 'y', 'z'],
        },
      ],
      questionIndex: 0,
      selectedOption: '',
      answers: [],
      formData: {
        name: '',
        email: '',
        password: '',
      },
    };
  },
  components: {
    Link,
  },
  methods: {
    nextQuestion() {
      if (this.selectedOption) {
        this.answers.push(this.selectedOption);
        this.selectedOption = '';
        this.questionIndex++;
      }
    },
    selectOptionAndNextQuestion(option) {
      // Set the selected option
      this.selectedOption = option;
      
      // Move to the next question
      this.nextQuestion();
    },
  },
};
</script>

<style>
#app{
    width: 100%;
}
body {
  font-family: "Lato", sans-serif;
  display: grid;
  place-items: center;
  box-sizing: border-box;
  min-height: 100vh;
}

#quiz {
  width: 80%;
    margin: 10px auto;
    padding: 16px;
    border-radius: 8px;
}

#quiz h1 {
  font-size: 40px;
  font-family: "Cabin", sans-serif;
  text-align: center;
}

.option {
  margin-bottom: 15px;
  padding: 16px 25px 16px 65px;
  border-radius: 0 8px 0 8px;
  box-shadow: 0 1px 23px 1px rgba(0, 0, 0, 0.07),
    inset 4px 0 0 0 rgba(157, 120, 254, 0.51);
  background-color: white;
  transition: background-color 0.3s;
  cursor: pointer;
  position: relative;
}

.option:hover {
  background: linear-gradient(to right, #9e78ff, #8daafc);
  color: #fff;
}

.option label i.fas {
  min-width: 25px;
}

.option input[type="radio"] {
  appearance: none;
  width: 25px;
  height: 25px;
  border: 1px solid #999;
  border-radius: 50%;
  outline: none;
  transition: 0.3s all linear;
  margin-right: 10px;
  opacity: 0.2;
  background: #9e78ff;
  position: absolute;
  left: 20px;
  margin-top: 0;
}

.option:hover input[type="radio"] {
  opacity: 1;
  background: #fff;
}

.option label {
  font-size: 18px;
  font-family: "Lato", sans-serif;
  font-weight: 300;
}

progress {
  width: 100%;
  height: 11px;
  margin-top: 20px;
  color: #000;
}

progress[value] {
  /* Reset the default appearance */
  -webkit-appearance: none;
  appearance: none;
  width: 100%;
  height: 11px;
  border: 1px solid #ddd;
}

progress[value]::-webkit-progress-bar {
  background-color: #fff;
}

progress[value]::-webkit-progress-value {
  background: linear-gradient(to right, #9e78ff, #8daafc);
}

.option input[type="radio"]:checked {
  background-color: #9e78ff;
}

/* iPads (landscape and portrait) ----------- */
@media only screen and (min-width: 601px) and (max-width: 1024px) {
  #quiz h1 {
    font-size: 35px;
  }

  .option label {
    font-size: 16px;
  }
}
/* Mobile devices (landscape and portrait) ----------- */
@media only screen and (max-width: 600px) {
  #quiz h1 {
    font-size: 30px;
  }

  .option label {
    font-size: 16px;
  }
  .option input[type="radio"] {
    width: 20px;
    height: 20px;
  }
  .option {
    padding: 14px 20px 14px 60px;
  }
}
</style>