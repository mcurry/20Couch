<div class="form-wrapper">
	<?php echo $this->element('providers/update_status') ?>
</div>

<script type="text/javascript">
	$(function(){
		$charsRemaining = $("#chars-remaining");
		$charsRemainingWrapper = $("#chars-remaining-wrapper");
		
		$("#ProviderStatus").keyup(function() {
			charsRemaining = 140 - this.value.length;
			$charsRemaining.html(charsRemaining);
			if(charsRemaining < 20 && charsRemaining >=0) {
				$charsRemainingWrapper.attr("class", "getting-close");
			} else if(charsRemaining < 0) {
				$charsRemainingWrapper.attr("class", "over-limit");
			}
		}).keyup();
	});
</script>