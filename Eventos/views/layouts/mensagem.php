<?php if (isset($_SESSION['mensagem'])): ?>

	<div class="alert alert-dark shadow-lg border-start border-warning border-4 text-white d-flex align-items-center mb-4 mt-2 fade show rounded-3" role="alert" style="animation: slideDown 0.4s ease-out;">
		
        <div class="bg-warning text-dark rounded-circle p-2 me-3 shadow d-flex justify-content-center align-items-center" style="width:40px; height:40px;">
           <i class="bi bi-bell-fill fs-5"></i>
        </div>

        <div class="flex-grow-1 fs-6 fw-normal lh-sm py-2 text-white">
			<?= $_SESSION['mensagem']; ?>
		</div>

		<button type="button" class="btn-close btn-close-white align-self-start mt-1 ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>

    <style>
        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>

<?php
	unset($_SESSION['mensagem']);
endif;
?>