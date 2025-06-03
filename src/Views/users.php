<?php
$content = <<<HTML
<div class="row" id="usersList">
    <!-- Users will be loaded here dynamically -->
</div>

<template id="userCardTemplate">
    <div class="col-md-4 mb-4">
        <div class="card user-card h-100">
            <div class="card-body">
                <h5 class="card-title user-name"></h5>
                <p class="card-text user-email"></p>
                <p class="card-text"><small class="text-muted user-date"></small></p>
                <div class="action-buttons">
                    <button class="btn btn-sm btn-primary edit-user">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger delete-user">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
HTML; 