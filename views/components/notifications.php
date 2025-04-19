<?php
// Function to display a notification and clear it from the session
function displayNotification($type) {
    if (isset($_SESSION[$type])) {
        $message = $_SESSION[$type];
        unset($_SESSION[$type]);
        
        $bgColor = '';
        $textColor = '';
        $icon = '';
        
        switch ($type) {
            case 'success':
                $bgColor = 'bg-green-100';
                $textColor = 'text-green-800';
                $borderColor = 'border-green-500';
                $icon = '<svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>';
                break;
            case 'error':
                $bgColor = 'bg-red-100';
                $textColor = 'text-red-800';
                $borderColor = 'border-red-500';
                $icon = '<svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>';
                break;
            case 'info':
                $bgColor = 'bg-blue-100';
                $textColor = 'text-blue-800';
                $borderColor = 'border-blue-500';
                $icon = '<svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 01-1-1v-4a1 1 0 112 0v4a1 1 0 01-1 1z" clip-rule="evenodd" />
                        </svg>';
                break;
            case 'warning':
                $bgColor = 'bg-yellow-100';
                $textColor = 'text-yellow-800';
                $borderColor = 'border-yellow-500';
                $icon = '<svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>';
                break;
        }
        
        echo '<div class="mb-4 rounded-md border-l-4 ' . $bgColor . ' ' . $textColor . ' border-' . $borderColor . ' p-4" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        ' . $icon . '
                    </div>
                    <div class="ml-3">
                        <p class="text-sm">' . htmlspecialchars($message) . '</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()" 
                                class="inline-flex rounded-md p-1.5 ' . $bgColor . ' ' . $textColor . ' hover:bg-' . substr($bgColor, 3, 5) . '-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-' . substr($borderColor, 7, 5) . '-500">
                                <span class="sr-only">Dismiss</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>';
    }
}
?>

<div class="notifications-container">
    <?php 
    // Display all types of notifications
    displayNotification('success');
    displayNotification('error');
    displayNotification('info');
    displayNotification('warning');
    ?>
</div>