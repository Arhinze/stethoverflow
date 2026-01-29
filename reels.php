<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");
Index_Segments::reels_header();

$reels_stmt = $pdo->prepare("SELECT * FROM videos ORDER BY video_id DESC LIMIT ?, ?");
$reels_stmt->execute([0,100]);
$reels_data = $reels_stmt->fetchAll(PDO::FETCH_OBJ);

?>

<div class="reels_body">
    <div class="reels-container">
    <?php foreach($reels_data as $rd) { ?>
        <div class="reel-video-wrapper">
            <a href="javascript:history.back()" class="back-button">
                <i class="fas fa-arrow-left"></i>
            </a>

            <video class="medical-video" loop muted playsinline>
                <source src="/static/videos/<?= $rd->video_url ?>" type="video/mp4">
            </video>

            <div class="status-icon"><i class="fas fa-play"></i></div>

            <div class="side-actions">
                <div class="action-btn"><i class="fas fa-heart"></i><span>1.2k</span></div>
                <div class="action-btn"><i class="fas fa-comment"></i><span>45</span></div>
                <div class="action-btn"><i class="fas fa-retweet"></i></div>
                <div class="action-btn"><i class="fas fa-share"></i></div>
                <div class="action-btn more-options"><i class="fas fa-ellipsis-vertical"></i></div>
            </div>

            <div class="video-overlay">
                <div class="bottom-info">
                    <h3>@StethOverflow</h3>
                    <p><?= $rd->description ?></p>
                </div>
                <div class="mute-toggle"><i class="fas fa-volume-mute"></i></div>
            </div>

            <div class="progress-container">
                <div class="progress-bar"></div>
            </div>
        </div>

        <div class="modal-overlay comment-overlay">
            <div class="modal-content">
                <div class="modal-header">Comments <span class="close-modal">&times;</span></div>
                <div class="comment-list">
                    <p><strong>Dr. Smith:</strong> Great clinical insight!</p>
                </div>
                <div class="comment-input">
                    <input type="text" placeholder="Add a medical comment...">
                    <button>Post</button>
                </div>
            </div>
        </div>
        
        <div class="modal-overlay options-overlay">
            <div class="modal-content white-bg">
                <div class="option-item"><i class="fas fa-bookmark"></i> Save Video</div>
                <div class="option-item"><i class="fas fa-flag"></i> Report Video</div>
                <div class="option-item close-options">Cancel</div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>

<div><!-- script for reels section -->
    <script>
        const options = {
            root: null, // Use the viewport
            threshold: 0.7 // Trigger when 70% of the video is visible
        };
    
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const video = entry.target;
                if (entry.isIntersecting) {
                    video.play();
                    video.muted = false; // Optional: Unmute on view
                } else {
                    video.pause();
                    video.currentTime = 0; // Reset video when scrolled away
                }
            });
        }, options);
    
        // Attach observer to all medical videos
        document.querySelectorAll('.medical-video').forEach(vid => {
            observer.observe(vid);
        });
    
        // Simple click-to-pause toggle
        document.querySelectorAll('.medical-video').forEach(vid => {
            vid.addEventListener('click', () => {
                if (vid.paused) vid.play();
                else vid.pause();
            });
        });

        document.querySelectorAll('.reel-video-wrapper').forEach(wrapper => {
            const video = wrapper.querySelector('video');
            const pBar = wrapper.querySelector('.progress-bar');
            const statusIcon = wrapper.querySelector('.status-icon');
            const muteBtn = wrapper.querySelector('.mute-toggle i');
        
            // 1. Progress Bar Logic
            video.addEventListener('timeupdate', () => {
                const percentage = (video.currentTime / video.duration) * 100;
                pBar.style.width = percentage + '%';
            });
        
            // 2. Play/Pause with Icon Animation
            video.addEventListener('click', () => {
                if (video.paused) {
                    video.play();
                    statusIcon.innerHTML = '<i class="fas fa-play"></i>';
                } else {
                    video.pause();
                    statusIcon.innerHTML = '<i class="fas fa-pause"></i>';
                }
                
                // Show icon briefly
                statusIcon.classList.add('show');
                setTimeout(() => statusIcon.classList.remove('show'), 500);
            });
        
            // 3. Mute/Unmute Logic
            wrapper.querySelector('.mute-toggle').addEventListener('click', (e) => {
                e.stopPropagation(); // Don't trigger the video play/pause
                video.muted = !video.muted;
                muteBtn.className = video.muted ? 'fas fa-volume-mute' : 'fas fa-volume-up';
            });
        });

        document.querySelectorAll('.reel-video-wrapper').forEach(wrapper => {
            const video = wrapper.querySelector('video');
            const commentBtn = wrapper.querySelector('.fa-comment').parentElement;
            const optionsBtn = wrapper.querySelector('.more-options');
            const commentModal = wrapper.querySelector('.comment-overlay');
            const optionsModal = wrapper.querySelector('.options-overlay');
        
            // Function to open modal
            function openModal(modal) {
                modal.classList.add('active');
                video.pause(); // Pause video while interacting
            }
        
            // Function to close modal
            function closeModal(modal) {
                modal.classList.remove('active');
                video.play();
            }
        
            // Event Listeners
            commentBtn.addEventListener('click', () => openModal(commentModal));
            optionsBtn.addEventListener('click', () => openModal(optionsModal));
        
            // Close on clicking X, Cancel, or the overlay itself
            wrapper.querySelectorAll('.close-modal, .close-options, .modal-overlay').forEach(el => {
                el.addEventListener('click', (e) => {
                    if(e.target === el) { // Only close if clicking the actual target
                        closeModal(commentModal);
                        closeModal(optionsModal);
                    }
                });
            });
        });
    </script>
</div>

</body>
</html>
<?php //Index_Segments::footer(); ?>