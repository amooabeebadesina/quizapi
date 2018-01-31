<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Libraries\Constant;
use App\Question;
use App\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{

    protected function validateQuizEntry(array $data)
    {
        return Validator::make($data, [
            'label' => 'required|string|max:255',
            'questions' => 'required',
        ]);
    }

    public function createQuiz(Request $request)
    {
        $user = $this->getUserFromToken($request);
        $validator = $this->validateQuizEntry($request->all());
        if ($validator->fails()) {
            return $this->sendErrorResponse($validator->messages());
        }
        $quiz = new Quiz();
        $quiz->label = $request->label;
        if ($user->quizzes()->save($quiz)) {
            $this->attachQuestionsToQuiz($request->questions, $quiz);
            return $this->sendSuccessResponse($quiz);
        } else {
            return $this->sendErrorResponse(Constant::QUIZ_CREATE_FAILED);
        }
    }

    protected function attachQuestionsToQuiz($questions_payload, Quiz $quiz)
    {
        foreach ($questions_payload as $question_payload) {
            $question = new Question();
            $question->label = $question_payload['label'];
            if ($quiz->questions()->save($question)) {
                $this->attachAnswersToQuestion($question, $question_payload['answers']);
            }
        }
    }

    protected function attachAnswersToQuestion($question, $data)
    {
        foreach ($data as $datum) {
            $answer = new Answer();
            $answer->label = $datum['label'];
            if (isset($datum['is_correct']) && $datum['is_correct']) {
                $answer->is_correct = 1;
            }
            $question->answers()->save($answer);
        }
    }

    public function getQuestions(Request $request, $id)
    {
        $quiz = Quiz::find($id);
        if ($quiz) {
            $questions = Question::where('quiz_id', $id)
                ->with('answers')->paginate(20);
            return $this->sendSuccessResponse($questions);
        } else {
            return $this->sendErrorResponse(Constant::QUIZ_NOT_FOUND);
        }
    }

    public function gradeQuiz(Request $request)
    {
        $correct = 0;
        $request_questions = $request->json();
        foreach ($request_questions as $question_request) {
            $answer = Answer::where(['question_id' => $question_request['question'], 'is_correct' => 1])
                                ->first();
            if ($answer && ($answer->id == $question_request['answer'])) {
                $correct++;
            }
        }
        return $this->sendSuccessResponse(['correct' => $correct]);
    }
}
