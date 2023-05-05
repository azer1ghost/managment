@extends('layouts.main')

@section('title', trans('translates.navbar.dashboard'))

@section('style')
    <style>
        /* Google Fonts - Poppins */
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap");

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }
        body {
            background-color: #e3f2fd;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f2f2f2;
        }

        ::-webkit-scrollbar-thumb {
            border-radius: 8px;
            background: #ccc;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #aaa;
        }

        .container {
            position: relative;
            min-width: 360px;
            width: 25%;
            border-radius: 8px;
            padding: 25px;
            /*margin: 85px auto 0;*/
            background-color: #fff;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }
        .container .input-field {
            position: relative;
            height: 64px;
            width: 100%;
        }
        .input-field textarea {
            height: 100%;
            width: 100%;
            outline: none;
            font-size: 18px;
            font-weight: 400;
            border-radius: 8px;
            padding: 18px 45px 18px 15px;
            border: 1px solid #ccc;
            resize: none;
        }
        .input-field textarea:focus {
            border-color: #4070f4;
        }
        textarea::-webkit-scrollbar {
            display: none;
        }
        .input-field .note-icon {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            pointer-events: none;
            font-size: 24px;
            color: #707070;
        }
        .input-field textarea:focus ~ .note-icon {
            color: #4070f4;
        }
        .container .todoLists {
            max-height: 75vh;
            overflow-y: auto;
            padding-right: 10px;
        }
        .todoLists .list {
            display: flex;
            align-items: center;
            list-style: none;
            background-color: #f2f2f2;
            padding: 20px 15px;
            border-radius: 8px;
            margin-top: 10px;
            position: relative;
            cursor: pointer;
        }
        .todoLists .list input {
            height: 16px;
            min-width: 16px;
            accent-color: #4070f4;
            pointer-events: none;
        }
        .todoLists .list .task {
            margin: 0 30px 0 15px;
            word-break: break-all;
        }
        .list input:checked ~ .task {
            text-decoration: line-through;
        }
        .todoLists .list i {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            font-size: 20px;
            color: #707070;
            padding: 5px;
            opacity: 0.6;
            display: none;
        }
        .todoLists .list:hover i {
            display: inline-flex;
        }
        .todoLists .list i:hover {
            opacity: 1;
        }
        .container .pending-tasks {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 25px;
        }
        .pending-tasks span {
            color: #333;
        }
        .pending-tasks .clear-button {
            padding: 6px 12px;
            outline: none;
            border: none;
            background: #4070f4;
            color: #fff;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
            pointer-events: none;
            white-space: nowrap;
        }
        .clear-button:hover {
            background-color: #0e4bf1;
        }
        @media screen and (max-width: 350px) {
            .container {
                padding: 25px 10px;
            }
        }

    </style>

    <style>
        body {
            margin: 0;
            background: #009578;
        }

        .app {
            display: grid;
            grid-template-columns: repeat(auto-fill, 200px);
            padding: 24px;
            gap: 24px;
        }

        #app {
            display: grid;
            grid-template-columns: repeat(auto-fill, 200px);
            padding: 24px;
            gap: 24px;
        }

        .note {
            height: 200px;
            box-sizing: border-box;
            padding: 16px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 7px rgba(0, 0, 0, 0.15);
            resize: none;
            font-family: sans-serif;
            font-size: 16px;
        }

        .add-note {
            height: 200px;
            border: none;
            outline: none;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            font-size: 120px;
            color: rgba(0, 0, 0, 0.5);
            cursor: pointer;
            transition: background 0.2s;
        }

        .add-note:hover {
            background: rgba(0, 0, 0, 0.2);
        }

    </style>
@endsection

@section('content')
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"/>
    <div class="row">
        <div class="col-8" style="border-right: black solid 2px; s">

            <div id="app">
                @foreach($notes as $note)
                    <textarea class="note" placeholder="Empty Sticky Note">{{$note->content}}</textarea>
                @endforeach
                <button class="add-note" type="button">+</button>
            </div>

        </div>
        <div class="col-4">
            <div class="container">
                <div class="input-field">
                    <textarea placeholder="Enter your new todo"></textarea>
                    <i class="uil uil-notes note-icon"></i>
                </div>

                <ul class="todoLists"></ul>

                <div class="pending-tasks">
                    <span>You have <span class="pending-num"> no </span> tasks pending.</span>
                    <button class="clear-button">Clear All</button>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('scripts')
    <script>
        //Getting all required elements
        const inputField = document.querySelector(".input-field textarea"),
            todoLists = document.querySelector(".todoLists"),
            pendingNum = document.querySelector(".pending-num"),
            clearButton = document.querySelector(".clear-button");

        //we will call this function while adding, deleting and checking-unchecking the task
        function allTasks() {
            let tasks = document.querySelectorAll(".pending");

            //if tasks' length is 0 then pending num text content will be no, if not then pending num value will be task's length
            pendingNum.textContent = tasks.length === 0 ? "no" : tasks.length;

            let allLists = document.querySelectorAll(".list");
            if (allLists.length > 0) {
                todoLists.style.marginTop = "20px";
                clearButton.style.pointerEvents = "auto";
                return;
            }
            todoLists.style.marginTop = "0px";
            clearButton.style.pointerEvents = "none";
        }

        //add task while we put value in text area and press enter
        inputField.addEventListener("keyup", (e) => {
            let inputVal = inputField.value.trim(); //trim fuction removes space of front and back of the inputed value

            //if enter button is clicked and inputed value length is greated than 0.
            if (e.key === "Enter" && inputVal.length > 0) {
                let liTag = ` <li class="list pending" onclick="handleStatus(this)">
          <input type="checkbox" />
          <span class="task">${inputVal}</span>
          <i class="uil uil-trash" onclick="deleteTask(this)"></i>
        </li>`;

                todoLists.insertAdjacentHTML("beforeend", liTag); //inserting li tag inside the todolist div
                inputField.value = ""; //removing value from input field
                allTasks();
            }
        });

        function handleStatus(e) {
            const checkbox = e.querySelector("input"); //getting checkbox
            checkbox.checked = checkbox.checked ? false : true;
            e.classList.toggle("pending");
            allTasks();
        }

        function deleteTask(e) {
            e.parentElement.remove(); //getting parent element and remove it
            allTasks();
        }

        clearButton.addEventListener("click", () => {
            todoLists.innerHTML = "";
            allTasks();
        });

    </script>

    <script>
        const notesContainer = document.getElementById("app");
        const addNoteButton = notesContainer.querySelector(".add-note");

        getNotes().forEach((note) => {
            const noteElement = createNoteElement(note.id, note.content);
            notesContainer.insertBefore(noteElement, addNoteButton);
        });

        addNoteButton.addEventListener("click", () => addNote());

        function getNotes() {
            return JSON.parse(localStorage.getItem("stickynotes-notes") || "[]");
        }

        function saveNotes(notes) {
            localStorage.setItem("stickynotes-notes", JSON.stringify(notes));
        }

        function createNoteElement(id, content) {
            const element = document.createElement("textarea");

            element.classList.add("note");
            element.value = content;
            element.placeholder = "Empty Sticky Note";

            element.addEventListener("change", () => {
                $.ajax({
                    type: "POST",
                    url: "/module/sendNote",
                    data: {
                        // id: targetNote.id,
                        content: element.value
                    },
                    success: function (response) {
                        console.log("Note updated successfully!");
                    },
                    error: function (xhr, status, error) {
                        console.error("Failed to update note: " + error);
                    }
                });
            });

            element.addEventListener("dblclick", () => {
                const doDelete = confirm(
                    "Are you sure you wish to delete this sticky note?"
                );

                if (doDelete) {
                    deleteNote(id, element);
                }
            });

            return element;
        }

        function addNote() {
            const notes = getNotes();
            const noteObject = {
                id: Math.floor(Math.random() * 100000),
                content: ""
            };

            const noteElement = createNoteElement(noteObject.id, noteObject.content);
            notesContainer.insertBefore(noteElement, addNoteButton);

            notes.push(noteObject);
            saveNotes(notes);
        }

        function updateNote(id, newContent) {
            const notes = getNotes();
            const targetNote = notes.filter((note) => note.id == id)[0];

            targetNote.content = newContent;
            saveNotes(notes);
        }

        function deleteNote(id, element) {
            const notes = getNotes().filter((note) => note.id != id);

            saveNotes(notes);
            notesContainer.removeChild(element);
        }



    </script>
@endsection