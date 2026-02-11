@extends('layouts.app')

@section('title')
Question
@endsection

<!-- <form action="{{ route('questions.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="category_id" id="category_id" value="{{$category_id}}" >

        <input type="file" name="file" >
        <button type="submit">Import Questions</button>
    </form> -->

@section('content')
<div>
    <div class="d-flex ms-3 mt-3">
        <!-- Add Question Button -->
        <form action="{{route('addQuestionView' , ['category_id'=>$category_id] , $absolute = false)}}"
            method="post">
            @csrf
            <input style="font-size: 15px" type="submit" value="Add Question" class="btn btn-primary">
        </form>

        <!-- Spacer between buttons (optional) -->
        <div class="ms-3"></div>

        <!-- Import CSV Button -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadCsvModal">
            Import CSV
        </button>
    </div>
    <div class="mt-4 mb-4"></div>

    <!-- Modal for uploading csv-->
    <div class="modal fade" id="uploadCsvModal" tabindex="-1" aria-labelledby="uploadCsvModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadCsvModalLabel">Upload CSV File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- File Upload Form -->
                    <form action="{{ route('questions.import') }}?category_id={{ request()->get('category_id') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Select CSV file</label>
                            <input class="form-control" type="file" id="formFile" name="file" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    @if(sizeof($questions) < 24)

        <!-- <div style="background-color: #ffdddd; border-left: 6px solid #f44336; width: 20%; margin-left: 1%; margin-top: 1%; float: left; margin-bottom: 1%">
        <p><strong>Danger !</strong> This category won't appear because the number of questions is less than 24</p>
</div> -->

@endif

</div>



<div>
<table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Questions</th>
                <th scope="col">Correct Answers</th>
                <th scope="col">Options</th>
                <th scope="col">Configuration</th>
            </tr>
        </thead>

        <tbody>
            @foreach($questions as $k => $question)

            <tr>
                <th>{{$k+1}}</th>
                <td>
                    <h5>{{$question->title}}</h5>
                </td>

                <td>
                    <h5>{{$question->answer}}</h5>
                </td>

                <td>
                    @if(count($answers[$question->id]) > 0)
                    <div class="dropdown">
                        <a class="btn btn-secondary dropdown-toggle" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            Answers
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <!-- Add Option button at the top of dropdown -->
                            <li class="dropdown-item text-center">
                                <button class="btn btn-success btn-sm" type="submit" data-bs-toggle="modal"
                                     data-bs-target="#addAnswerModal-{{ $question->id }}"
                                     @if(count($answers[$question->id]) >= 4) disabled @endif>
                                     + Add Option
                                </button>
                            </li>
                            <hr>
                            <!-- List existing answers with edit and delete options -->
                            @foreach($answers[$question->id] as $answer)
                            <li class="d-flex justify-content-between align-items-center">
                                <button class="dropdown-item align-items-center" data-bs-toggle="modal" data-bs-target="#editAnswerModal-{{ $answer->id }}">{{ $answer->text }}</button>

                                <!-- Delete button for each answer -->
                                <form action="{{ route('deleteAnswer', ['answer_id' => $answer->id]) }}" method="post" onsubmit="return confirm('Are you sure you want to delete this answer?');">
                                    @csrf
                                    <button class="btn btn-danger btn-sm" type="submit">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @else
                    <h4>No Answers</h4>
                    @endif
                </td>

                <td>
                    <!-- Modal for adding new answer -->
                    <div class="modal fade" id="addAnswerModal-{{ $question->id }}" tabindex="-1" aria-labelledby="addAnswerModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addAnswerModalLabel">Add Answer for Question: {{ $question->title }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="addAnswerForm-{{ $question->id }}" action="{{ route('addAnswerFun', ['question_id' => $question->id], $absolute = false) }}" method="post">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="answerText" class="form-label">Answer Text</label>
                                            <input type="text" name="text" class="form-control" id="answerText" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal for editing answer -->
                    @foreach($answers[$question->id] as $answer)
                    <div class="modal fade" id="editAnswerModal-{{ $answer->id }}" tabindex="-1" aria-labelledby="editAnswerModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editAnswerModalLabel">Edit Answer for : {{ $answer->text }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="editAnswerForm-{{ $answer->id }}" action="{{ route('updateAnswerFun', ['answer_id' => $answer->id], $absolute = false) }}" method="post">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="editAnswerText" class="form-label">Answer Text</label>
                                            <input type="text" name="text" value="{{ $answer->text }}" class="form-control" id="editAnswerText" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div>
                        <form style="float:left;margin-right: 10px"
                            action="{{route('addAnswerView' , ['question_id'=>$question->id] , $absolute = false)}}"
                            method="post">
                            @csrf
                            <!-- <input style="font-size: 15px" type="submit" value="Add Answer" class="btn btn-primary"> -->
                        </form>

                        <form style="float:left;margin-right: 10px"
                            action="{{route('updateQuestionView' , ['category_id'=>$category_id ,'question_id'=>$question->id , 'title'=>$question->title ,'answer'=>$question->answer] , $absolute = false)}}"
                            method="post">
                            @csrf
                            <input style="font-size: 15px" type="submit" value="Edit Question" class="btn btn-primary">
                        </form>

                        <form style="float:left;"
                            action="{{route('deleteQuestion' , ['question_id'=>$question->id , 'category_id'=>$category_id], $absolute = false)}}"
                            method="post">
                            @csrf
                            <input style="font-size: 15px" type="submit" value="Delete Question" class="btn btn-danger">
                        </form>


                    </div>
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>
</div>

@endsection