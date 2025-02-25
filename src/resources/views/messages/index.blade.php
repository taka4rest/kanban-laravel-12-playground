@extends('layouts.app')

@section('title', 'Message Board')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center p-3 mb-3 bg-light border-bottom rounded shadow-sm">
            <h1 class="h3 mb-0 text-secondary">Message Board</h1>
            <button onclick="document.getElementById('messageModal').style.display='block'"
                    class="btn btn-outline-primary">
                <i class="fas fa-plus-circle me-1"></i> Add Message
            </button>
        </div>

        <!-- Modal Form -->
        <div id="messageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.3); z-index: 1000;">
            <div class="modal-dialog" style="max-width: 500px; margin: 100px auto;">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light border-bottom-0">
                        <h5 class="modal-title text-secondary">Add New Message</h5>
                        <button type="button" onclick="document.getElementById('messageModal').style.display='none'"
                                class="btn-close" aria-label="Close"></button>
                    </div>
                    <form id="messageForm" action="{{ route('messages.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="title" class="form-label text-secondary">Title:</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label text-secondary">Content:</label>
                                <textarea class="form-control" id="content" name="content" required rows="5"></textarea>
                            </div>
                            <input type="hidden" name="status" value="todo">
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" onclick="document.getElementById('messageModal').style.display='none'"
                                    class="btn btn-outline-secondary">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-outline-primary">
                                Add
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Trello Style Message Board -->
        <div id="trello-root" data-messages='@json($messages)'></div>
    </div>

    @if(session('status'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-light text-success">
                    <strong class="me-auto">Success</strong>
                    <button type="button" class="btn-close" onclick="this.parentElement.parentElement.parentElement.remove()" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{ session('status') }}
                </div>
            </div>
        </div>
    @endif
@endsection
