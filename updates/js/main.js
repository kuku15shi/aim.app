document.addEventListener('DOMContentLoaded', function() {
    const loginBox = document.getElementById('login-box');
    const registerBox = document.getElementById('register-box');
    const switchTextContainer = document.getElementById('switch-text');

    function setupSwitchListener() {
        const switchToRegisterLink = document.getElementById('switch-to-register');
        const switchToLoginLink = document.getElementById('switch-to-login');

        if (switchToRegisterLink) {
            switchToRegisterLink.addEventListener('click', function(e) {
                e.preventDefault();
                loginBox.style.display = 'none';
                registerBox.style.display = 'block';
                switchTextContainer.innerHTML = 'Have an account? <a href="#" id="switch-to-login">Log in</a>';
                setupSwitchListener(); // Re-attach listener
            });
        }

        if (switchToLoginLink) {
            switchToLoginLink.addEventListener('click', function(e) {
                e.preventDefault();
                loginBox.style.display = 'block';
                registerBox.style.display = 'none';
                switchTextContainer.innerHTML = "Don't have an account? <a href='#' id='switch-to-register'>Sign up</a>";
                setupSwitchListener(); // Re-attach listener
            });
        }
    }

    setupSwitchListener();
});
// Add this function to your existing js/main.js file

function handleLike(postId, buttonElement) {
    fetch('like.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `post_id=${postId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the button appearance and text
            const icon = buttonElement.querySelector('i');
            const countElement = buttonElement.nextElementSibling;
            
            if (data.liked) {
                icon.classList.remove('far'); // Use solid heart
                icon.classList.add('fas', 'text-danger'); // Make it red
            } else {
                icon.classList.remove('fas', 'text-danger');
                icon.classList.add('far'); // Use hollow heart
            }
            
            // Update the like count displayed next to the button
            countElement.textContent = data.total_likes;
        } else {
            alert('Error processing like: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An unexpected error occurred.');
    });
}
// Add this function to your existing js/main.js file

function sharePost(postId) {
    // In a real app, this would be the actual public URL:
    const postUrl = `${window.location.origin}/feed.php?post=${postId}`;
    
    navigator.clipboard.writeText(postUrl).then(() => {
        alert("Link copied to clipboard: " + postUrl);
    }).catch(err => {
        console.error('Could not copy text: ', err);
    });
}