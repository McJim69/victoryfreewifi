<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="rolloutModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="rolloutModalLabel">SITE STATUS</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<div style="width:auto;height:auto;text-align:center">
					<h1>
						Active: <?php echo $active;?>
						<i class="fa fa-refresh fa-spin" aria-hidden="true"></i>
						Down: <?php echo $downed;?>
					</h1> 
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>