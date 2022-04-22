<?php
if(!empty($_SESSION['username'])){
    $clouds = [];
    $query = _que('SELECT * FROM `vm` INNER JOIN `ip_address` ON `vm`.`ref` = `ip_address`.`uuid` WHERE `cusid` = ?',[$_SESSION['username']]);
    if(!is_array($query) || @!isset($query['failed'])) {
        $clouds = $query->fetchAll(PDO::FETCH_ASSOC);
    }

    $hosting = [];
    $query = _que('SELECT * FROM `hosting` WHERE `cusid` = ?',[$_SESSION['username']]);
    if(!is_array($query) || @!isset($query['failed'])) {
        $hosting = $query->fetchAll(PDO::FETCH_ASSOC);
    }
?>
	<div class="container pb-5">
		<div class="row">
		<div class="col-md-4 mb-3">
			<div class="card">
					<div class="list-group" id="service" role="tablist">
								<a class="list-group-item list-group-item-action active pt-3 pb-3" id="vps-tab" data-toggle="tab" href="#vps" role="tab" aria-controls="vps" aria-selected="true"><i class="fas fa-server mr-2"></i>Cloud Server</a>
								<a class="list-group-item list-group-item-action pt-3 pb-3" id="hosting-tab" data-toggle="tab" href="#hosting" role="tab" aria-controls="hosting" aria-selected="false"><i class="fas fa-cloud mr-2"></i>Cloud Hosting</a>
					</div>
			</div>
		</div>
		<div class="col-md-8">
		<div class="card">
			<div class="card-body">
				<div class="tab-content" id="service-content">
					<div class="tab-pane fade show active" id="vps" role="tabpanel" aria-labelledby="vps-tab">

						<div>
						<?php
							if(!empty($clouds)) {
						?>
								<h3><i class="fas fa-layer-group mr-2"></i><?= L::stack?></h3>
								<hr>
							<?php
								foreach($clouds as $cloud) {
							?>
										<div class="card mb-0">
											<div class="card-body d-flex flex-row flex-sm-row justify-content-between align-items-center">
												<div class="text-left mb-2">
													<p class="text-uppercase text-muted card-title mb-0"><?=$cloud['user_label']?></p>
													<h6 class="card-subtitle card-price mb-0" style="font-size: 20px;"><?=$cloud['ipv4']?></h6>
													<p class="mb-0">
														<i class="fas fa-coins" style="color: rgba(248,227,35,0.88);"></i> <?=$cloud['base_price']*$cloud['lenght']?>฿ / <?=$cloud['lenght']?> <?= L::day?> | 
														<i class="fa fa-calendar" style="color: rgba(237,0,0,0.69);"></i> <?=$cloud['timestamp']?>
													</p>
												</div>
												<div>
													<a class="btn btn-success btn-sm" href="?page=cloud&ref=<?=$cloud['ref']?>">
														<i class="fa fa-sliders-h"></i>
														<div class="d-none d-sm-inline">
															<?= L::setting?>
														</div>
													</a>
												</div>
											</div>
										</div>
						<?php
								}
							} else {
						?>
							<div class="text-center mt-5 pt-5 mb-5 pb-4">
								<h1 class="display-3 mb-5"><i class="fal fa-dolly-flatbed-empty"></i></h1>
								<h4><?= L::noserver1?></h4>
								<p><?= L::noserver2?></p>
							</div>
						<?php
							}
						?>
						</div>

					</div>
					<div class="tab-pane fade" id="hosting" role="tabpanel" aria-labelledby="hosting-tab">
						<div>
							<?php
								if(!empty($hosting)) {
							?>
								<h3><i class="fas fa-server mr-2"></i><?= L::hosting?></h3>
								<hr>
							<?php
									foreach($hosting as $h) {
							?>
										<div class="card mb-0">
											<div class="card-body d-flex flex-row flex-sm-row justify-content-between align-items-center">
												<div class="text-left mb-2">
													<p class="text-uppercase text-muted card-title mb-0">43.229.151.97</p>
													<h6 class="card-subtitle card-price mb-0" style="font-size: 20px;"><?=$h['def_domain']?></h6>
													<p class="mb-0">
														<i class="fas fa-coins" style="color: rgba(248,227,35,0.88);"></i> <?=$h['base_price']?>฿ / <?=$h['duration']?> <?= L::day?> | 
														<i class="fa fa-calendar" style="color: rgba(237,0,0,0.69);"></i> <?=$h['expiration_date']?>
													</p>
												</div>
												<div>
													<a class="btn btn-success btn-sm" href="?page=hosting&id=<?=$h['id']?>">
														<i class="fa fa-sliders-h"></i>
														<div class="d-none d-sm-inline">
															<?= L::setting?>
														</div>
													</a>
												</div>
											</div>
										</div>
							<?php
									}
								} else {
							?>
								<div class="text-center mt-5 pt-5 mb-5 pb-4">
									<h1 class="display-3 mb-5"><i class="fal fa-dolly-flatbed-empty"></i></h1>
									<h4><?= L::nohosting1?></h4>
									<p><?= L::nohosting2?></p>
								</div>
							<?php
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
	</div>
</div>
<?php
	} else {
	
		$pages=[];
		$query = _que('SELECT * FROM pages WHERE `page`="home"');
		if(!is_array($query) || @!isset($query['failed'])){
		  $pages = $query->fetchAll(PDO::FETCH_ASSOC);
		}
		$json = json_decode($pages[0]['json']);
		$zone1 = $json->zone1;
		$zone1 = json_decode($json->zone1);
		$zone2 = json_decode($json->zone2);
		$plan1 = json_decode($json->plan1);
		$plan2 = json_decode($json->plan2);
		$plan3 = json_decode($json->plan3);
?>
	<div class="intro-x2 py-5">
		<div class="container py-5">
			<div class="row align-items-center">
				<div class="col-lg-7 text-white text-lg-left text-center">
					<h1 class="font-weight-bold mb-0"><?= L::welcomedrite1?></h1>
					<h1 class="font-weight-bold mb-4"><?= L::welcomedrite2?></h1>
					<h4 class="mb-4"><?= L::homeintro?></h4>
				</div>
				<div class="col-lg-5">
					<?php   
						include_once '../components/card/login.php';
					?>
				</div>
			</div>
		</div>
	</div>
    <section class="services-area" id="whydrite">
		<div class="container text-lg-left text-md-left text-sm-left text-xs-left text-center">
			<div class="row">
				<div class="col-lg-12">
					<div class="section-title pb-10">
						<h4 class="title"><?php echo $zone1->title; ?></h4>
						<p class="text"><?php echo $zone1->desc; ?></p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="row">
						<div class="col-md-6">
							<div class="services-content mt-40 d-sm-flex">
								<div class="services-icon">
									<i class="fal fa-tachometer-alt"></i>
								</div>
								<div class="services-content media-body">
									<h4 class="services-title"><?php echo $zone1->i1_title; ?></h4>
									<p class="text"><?php echo $zone1->i1_desc1; ?><br>
									<?php echo $zone1->i1_desc2; ?></p>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="services-content mt-40 d-sm-flex">
								<div class="services-icon">
									<i class="fal fa-wand-magic"></i>
								</div>
								<div class="services-content media-body">
									<h4 class="services-title"><?php echo $zone1->i2_title; ?></h4>
									<p class="text"><?php echo $zone1->i2_desc1; ?><br>
									<?php echo $zone1->i2_desc2; ?></p>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="services-content mt-40 d-sm-flex">
								<div class="services-icon">
									<i class="fal fa-money-bill"></i>
								</div>
								<div class="services-content media-body">
									<h4 class="services-title"><?php echo $zone1->i3_title; ?></h4>
									<p class="text"><?php echo $zone1->i3_desc1; ?><br>
									<?php echo $zone1->i3_desc2; ?></p>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="services-content mt-40 d-sm-flex">
								<div class="services-icon">
									<i class="fal fa-wallet"></i>
								</div>
								<div class="services-content media-body">
									<h4 class="services-title"><?php echo $zone1->i4_title; ?></h4>
									<p class="text"><?php echo $zone1->i4_desc1; ?><br>
									<?php echo $zone1->i4_desc2; ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--<div class="services-image d-lg-flex align-items-center">
			<div class="image"><img alt="Services" src="assets/services.png"></div>
		</div>-->
    </section>
    <section class="pricing-area" id="pricedrite">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-6">
					<div class="section-title text-center pb-5">
						<h4 class="title"><?php echo $zone2->title; ?></h4>
						<p class="text"><?php echo $zone2->desc1; ?><br>
						<?php echo $zone2->desc2; ?></p>
					</div>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-lg-4 col-md-7 col-sm-9">
					<div class="single-pricing pro mt-40">
						<div class="pricing-header">
							<h5 class="sub-title"><?php echo $plan1->title; ?></h5>
							<span class="price"><?php echo $plan1->price; ?></span>
							<p class="basic-pkg"><?php echo $plan1->note; ?></p>
						</div>
						<div class="pricing-list">
							<ul>
								<li><i class="lni-check-mark-circle"></i> <i class="fal fa-microchip"></i> <?php echo $plan1->cpu; ?></li>
								<li><i class="lni-check-mark-circle"></i> <i class="fal fa-memory"></i> <?php echo $plan1->ram; ?></li>
								<li><i class="lni-check-mark-circle"></i> <i class="fal fa-hdd"></i> <?php echo $plan1->disk; ?></li>
								<li><i class="lni-check-mark-circle"></i> <i class="fal fa-headset"></i> <?php echo $plan1->support; ?></li>
							</ul>
						</div>
						<div class="pricing-btn text-center">
							<a class="main-btn" href="https://controlpanel.craft.in.th/?page=deploy_cloud" target="__blank"><i class="fal fa-server"></i> ดูคลาวด์เซิร์ฟเวอร์เพิ่มเติม</a>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-7 col-sm-9">
					<div class="single-pricing pro mt-40">
						<div class="pricing-header">
							<h5 class="sub-title"><?php echo $plan2->title; ?></h5>
							<span class="price"><?php echo $plan2->price; ?></span>
							<p class="basic-pkg"><?php echo $plan2->note; ?></p>
						</div>
						<div class="pricing-list">
							<ul>
								<li><i class="lni-check-mark-circle"></i> <i class="fal fa-microchip"></i> <?php echo $plan2->cpu; ?></li>
								<li><i class="lni-check-mark-circle"></i> <i class="fal fa-memory"></i> <?php echo $plan2->ram; ?></li>
								<li><i class="lni-check-mark-circle"></i> <i class="fal fa-hdd"></i> <?php echo $plan2->disk; ?></li>
								<li><i class="lni-check-mark-circle"></i> <i class="fal fa-headset"></i> <?php echo $plan2->support; ?></li>
							</ul>
						</div>
						<div class="pricing-btn text-center">
							<a class="main-btn" href="https://controlpanel.craft.in.th/?page=deploy_dedicated" target="__blank"><i class="fal fa-server"></i> ดูเช่าเครื่องเซิร์ฟเวอร์เพิ่มเติม</a>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-7 col-sm-9">
					<div class="single-pricing pro mt-40">
						<div class="pricing-header">
							<h5 class="sub-title"><?php echo $plan3->title; ?></h5>
							<span class="price"><?php echo $plan3->price; ?></span>
							<p class="basic-pkg"><?php echo $plan3->note; ?></p>
						</div>
						<div class="pricing-list">
							<ul>
								<li><i class="lni-check-mark-circle"></i> <i class="fal fa-wifi"></i> <?php echo $plan3->net; ?></li>
								<li><i class="lni-check-mark-circle"></i> <i class="fal fa-location-arrow"></i> <?php echo $plan3->ip; ?></li>
								<li><i class="lni-check-mark-circle"></i> <i class="fal fa-plug"></i> <?php echo $plan3->power; ?></li>
								<li><i class="lni-check-mark-circle"></i> <i class="fal fa-headset"></i> <?php echo $plan3->support; ?></li>
							</ul>
						</div>
						<div class="pricing-btn text-center">
							<a class="main-btn" href="https://controlpanel.craft.in.th/?page=deploy_colocation" target="__blank"><i class="fal fa-server"></i> ดูวางเครื่องเซิร์ฟเวอร์เพิ่มเติม</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
    <?php
}
?>
