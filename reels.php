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
                <i class="fas fa-arrow-left"></i> &nbsp; StethOverflow
            </a>

            <video class="medical-video" loop muted playsinline>
                <source src="/static/videos/<?= $rd->video_url ?>" type="video/mp4">
            </video>

            <div class="status-icon"><i class="fas fa-play"></i></div>

            <div class="side-actions">
                <div class="action-btn"><i class="fas fa-heart"></i><span>1.2k</span></div>
                <div class="action-btn" onclick="openComments(<?=$rd->video_id?>)"><i class="fas fa-comment"></i><span>45</span></div>
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
    <?php } ?>
    </div>
</div>

<div><!-- script for reels section -->
    <script>
        document.querySelectorAll('.reel-video-wrapper').forEach(wrapper => {
            const video = wrapper.querySelector('video');
            const statusIcon = wrapper.querySelector('.status-icon');
            const commentBtn = wrapper.querySelector('.fa-comment').closest('.action-btn');
            const optionsBtn = wrapper.querySelector('.more-options');
            const commentModal = wrapper.querySelector('.comment-overlay');
            const optionsModal = wrapper.querySelector('.options-overlay');
            const muteBtn = wrapper.querySelector('.mute-toggle i');
        
            // --- 1. PLAY/PAUSE LOGIC ---
            video.addEventListener('click', (e) => {
                // Only trigger if we aren't clicking a UI button
                if (e.target.tagName !== 'VIDEO') return;
        
                if (video.paused) {
                    video.play();
                    statusIcon.innerHTML = '<i class="fas fa-play"></i>';
                } else {
                    video.pause();
                    statusIcon.innerHTML = '<i class="fas fa-pause"></i>';
                    // Flag to tell Observer NOT to auto-resume while user has it paused
                    video.dataset.manualPause = "true"; 
                }
                
                statusIcon.classList.add('show');
                setTimeout(() => statusIcon.classList.remove('show'), 500);
            });
        
            // --- 2. MODAL LOGIC ---
            const toggleModal = (modal, show) => {
                if (show) {
                    modal.classList.add('active');
                    video.pause();
                } else {
                    modal.classList.remove('active');
                    // Only resume if it wasn't manually paused before opening modal
                    if (video.dataset.manualPause !== "true") video.play();
                }
            };
        
            commentBtn.addEventListener('click', (e) => {
                e.stopPropagation(); // Prevents clicking the video underneath
                toggleModal(commentModal, true);
            });
        
            optionsBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleModal(optionsModal, true);
            });
        
            // Close Modals when clicking the X or the dark overlay
            wrapper.querySelectorAll('.close-modal, .close-options, .modal-overlay').forEach(el => {
                el.addEventListener('click', (e) => {
                    if (e.target === el || el.classList.contains('close-modal') || el.classList.contains('close-options')) {
                        toggleModal(commentModal, false);
                        toggleModal(optionsModal, false);
                    }
                });
            });
        
            // --- 3. MUTE LOGIC ---
            wrapper.querySelector('.mute-toggle').addEventListener('click', (e) => {
                e.stopPropagation();
                video.muted = !video.muted;
                muteBtn.className = video.muted ? 'fas fa-volume-mute' : 'fas fa-volume-up';
            });
        });
        
        // --- 4. UPDATED OBSERVER ---
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const video = entry.target;
                if (entry.isIntersecting) {
                    // Only auto-play if the user didn't manually pause it
                    if (video.dataset.manualPause !== "true") {
                        video.play();
                    }
                } else {
                    video.pause();
                    video.dataset.manualPause = "false"; // Reset when scrolled away
                }
            });
        }, { threshold: 0.7 });
        
        document.querySelectorAll('.medical-video').forEach(vid => observer.observe(vid));

        // Function to Open Modals
        function openComments(videoId) {
            const modal = document.getElementById('global-comment-modal');
            modal.classList.add('active');
            // You can use videoId later to fetch specific comments via AJAX
        }
        
        function openOptions(videoId) {
            const modal = document.getElementById('global-options-modal');
            modal.classList.add('active');
        }
        
        function closeAllModals() {
            document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('active'));
        }
        
        // Close when clicking the dark area
        window.onclick = function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                closeAllModals();
            }
        }
    </script>
</div>


</body>
<div id="global-comment-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">Comments <span class="close-modal" onclick="closeAllModals()">&times;</span></div>
        <div id="comment-body" class="comment-list">
            </div>
        <div class="comment-input">
            <input type="text" id="new-comment" class="reels-input" placeholder="Add a medical comment...">
            <button onclick="submitComment()" class="button">Post</button>
        </div>
    </div>
</div>

<div id="global-options-modal" class="modal-overlay">
    <div class="modal-content white-bg">
        <div class="option-item"><i class="fas fa-bookmark"></i> Save Video</div>
        <div class="option-item"><i class="fas fa-flag"></i> Report Video</div>
        <div class="option-item close-options" onclick="closeAllModals()">Cancel</div>
    </div>
</div>

</html>
<?php //Index_Segments::footer(); ?>