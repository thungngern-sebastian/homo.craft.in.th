<?php
    if(empty($_SESSION['username'])){
        die('<script>window.location.replace("?page=home")</script>');
    }

?>
<div class="container pb-5">
    <div class="card">
        <div class="card-body">
            <h5><i class="fas fa-id-card-alt"></i> <?= L::contact?></h5>
            <hr>
			<div class="mw-50 mx-auto mt-5 mb-4">
            <div class="embed-responsive">
				<div class="row">
					<div class="col-md-8">
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3876.2211107057738!2d100.52424571526427!3d13.705054002066456!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e298b39932799d%3A0x32d7c4be64a03d45!2zMTg0OC84IOC4i-C4reC4oiDguIjguLHguJnguJfguJnguYwgMjMvMiDguYHguILguKfguIcg4LiX4Li44LmI4LiH4Lin4Lix4LiU4LiU4Lit4LiZIOC5gOC4guC4lSDguKrguLLguJfguKMg4LiB4Lij4Li44LiH4LmA4LiX4Lie4Lih4Lir4Liy4LiZ4LiE4LijIDEwMTIw!5e0!3m2!1sth!2sth!4v1585308436001!5m2!1sth!2sth" class="embed-responsive-item" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
					</div>
					<div class="col-md-4">
						<h2><b>Contact US</b></h2><h5><?= L::contact?></h5><p class="mt-4 mb-4"><?= L::address?></p>
						<p class="mb-2"><i class="fas fa-phone-alt mr-2"></i>064-661-6749</p>
						<p class="mb-2"><i class="fas fa-envelope mr-2"></i>support@craft.in.th</p>
						<p class="mb-2"><i class="fab fa-facebook mr-2"></i><a class="text-reset" href="https://www.facebook.com/Craft.in.th">Craft.in.th</a></p>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
</div>