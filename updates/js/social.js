document.addEventListener('DOMContentLoaded', function () {

    // Toggle Like
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const postId = this.getAttribute('data-post-id');
            const icon = this;
            const countSpan = document.getElementById('like-count-' + postId);

            const formData = new FormData();
            formData.append('post_id', postId);

            fetch('api/toggle_like.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.action === 'liked') {
                            icon.classList.remove('far');
                            icon.classList.add('fas', 'text-danger');
                        } else {
                            icon.classList.remove('fas', 'text-danger');
                            icon.classList.add('far');
                        }
                        countSpan.textContent = data.new_count;
                    } else {
                        console.error('Error:', data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Toggle Comments Section
    document.querySelectorAll('.comment-toggle-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const postId = this.getAttribute('data-post-id');
            const commentSection = document.getElementById('comments-' + postId);
            const list = document.getElementById('comments-list-' + postId);

            if (commentSection.style.display === 'none') {
                // Open and fetch comments
                commentSection.style.display = 'block';

                fetch(`api/get_comments.php?post_id=${postId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            list.innerHTML = '';
                            data.comments.forEach(c => {
                                const p = document.createElement('div');
                                p.className = 'mb-1 small d-flex justify-content-between align-items-center comment-item';
                                p.innerHTML = `
                                    <span><strong>${c.username}</strong> ${c.comment}</span>
                                    ${c.is_mine ? `<i class="fas fa-trash text-muted delete-comment-btn" data-comment-id="${c.id}" style="cursor:pointer; font-size: 0.8rem;"></i>` : ''}
                                `;
                                list.appendChild(p);
                            });
                            attachDeleteHandlers();
                        }
                    });
            } else {
                commentSection.style.display = 'none';
            }
        });
    });

    // Dynamic Post Button State
    document.querySelectorAll('.insta-comment-input').forEach(input => {
        input.addEventListener('input', function () {
            const postId = this.id.split('-')[2]; // comment-input-123
            const btn = document.querySelector(`.post-comment-btn[data-post-id="${postId}"]`);

            if (this.value.trim().length > 0) {
                btn.removeAttribute('disabled');
            } else {
                btn.setAttribute('disabled', 'true');
            }
        });
    });

    // Create a function to attach handlers since elements are dynamic
    function attachDeleteHandlers() {
        document.querySelectorAll('.delete-comment-btn').forEach(btn => {
            btn.onclick = function () { // Use onclick to avoid duplicate listeners
                if (!confirm("Delete this comment?")) return;

                const commentId = this.getAttribute('data-comment-id');
                const commentDiv = this.closest('.comment-item');

                const formData = new FormData();
                formData.append('comment_id', commentId);

                fetch('api/delete_comment.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            commentDiv.remove();
                        } else {
                            alert(data.message);
                        }
                    });
            };
        });
    }


    // Post Comment
    document.querySelectorAll('.post-comment-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const postId = this.getAttribute('data-post-id');
            const input = document.getElementById('comment-input-' + postId);
            const comment = input.value;
            const list = document.getElementById('comments-list-' + postId);

            if (!comment.trim()) return;

            const formData = new FormData();
            formData.append('post_id', postId);
            formData.append('comment', comment);

            fetch('api/add_comment.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const p = document.createElement('div');
                        p.className = 'mb-1 small d-flex justify-content-between align-items-center comment-item';
                        // New comment is ours by definition
                        p.innerHTML = `
                             <span><strong>${data.username}</strong> ${data.comment}</span>
                             <i class="fas fa-trash text-muted delete-comment-btn" style="cursor:pointer; font-size: 0.8rem;" onclick="location.reload()"></i> 
                         `;
                        // Better: Just append text.
                        p.innerHTML = `<span><strong>${data.username}</strong> ${data.comment}</span>`;

                        list.appendChild(p);
                        input.value = ''; // Clear input
                        // Reset button state
                        btn.setAttribute('disabled', 'true');
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
        });
    });

    // Share Functionality (Copy Link)
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const postId = this.getAttribute('data-post-id');
            const shareUrl = window.location.origin + window.location.pathname + '#post-' + postId;

            navigator.clipboard.writeText(shareUrl).then(() => {
                alert('Link copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy: ', err);
                prompt("Copy this link:", shareUrl); // Fallback
            });
        });
    });

    // Video Play/Pause Interaction
    document.querySelectorAll('.video-container').forEach(container => {
        const video = container.querySelector('video');
        const overlay = container.querySelector('.video-overlay');

        if (video) {
            // Initial Check: If autoplay started before script loaded
            if (!video.paused) {
                container.classList.add('playing');
            }

            // Click handler
            container.addEventListener('click', function (e) {
                if (video.paused) {
                    video.play().then(() => {
                        container.classList.add('playing');
                    }).catch(err => {
                        console.error("Video play failed:", err);
                        // Fallback: Try muted play
                        video.muted = true;
                        video.play().then(() => {
                            container.classList.add('playing');
                        });
                    });
                } else {
                    video.pause();
                    container.classList.remove('playing');
                }
            });

            // Sync state events
            video.addEventListener('play', () => container.classList.add('playing'));
            video.addEventListener('pause', () => container.classList.remove('playing'));
            video.addEventListener('ended', () => container.classList.remove('playing'));
        }
    });

});
