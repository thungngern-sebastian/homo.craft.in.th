<?php
    if(empty($_SESSION['username'])){
        die('<script>window.location.replace("?page=home")</script>');
    }

?>
<div class="container pb-5">
 <div class="card">
    <div class="card-body">
        <h4><i class="fas fa-server"></i> <?= L::colocation?></h4>
        <hr>
        <div class="py-2 text-center mt-5 pt-5 pb-5">
                <h3>Co-Location</h3>
                <p>วางเครื่อง Server</p>
        </div>
        <div class="row justify-content-center mt-5 mb-5">
            <div class="col-lg-2">
                <div class="card text-center mb-3">
                    <div class="p-2 border-bottom">
                    <h4><i class="fas fa-server"></i></h4>
                    1U RACK</div>
                    <div class="p-2 border-bottom">
                        <small>Network 1 Gbps</small><br>
                        <small class="text-muted">10/10 Mbps Inter</small><br>
                        <small>1 Public IP</small><br>
                        <small>Power 0.5 AMP</small><br>
                        <small>Free Firewall</small>
                    </div>
                    <div class="p-2">
                        1,700 THB/Month
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="card text-center mb-3">
                    <div class="p-2 border-bottom">
                    <h4><i class="fas fa-server"></i></h4>
                    2U RACK</div>
                    <div class="p-2 border-bottom">
                        <small>Network 1 Gbps</small><br>
                        <small class="text-muted">10/10 Mbps Inter</small><br>
                        <small>2 Public IP</small><br>
                        <small>Power 1 AMP</small><br>
                        <small>Free Firewall</small>
                    </div>
                    <div class="p-2">
                        3,000 THB/Month
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="card text-center mb-3">
                    <div class="p-2 border-bottom">
                    <h4><i class="fas fa-server"></i></h4>
                    4U RACK</div>
                    <div class="p-2 border-bottom">
                        <small>Network 1 Gbps</small><br>
                        <small class="text-muted">10/10 Mbps Inter</small><br>
                        <small>4 Public IP</small><br>
                        <small>Power 2 AMP</small><br>
                        <small>Free Firewall</small>
                    </div>
                    <div class="p-2">
                        6,000 THB/Month
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="card text-center mb-3">
                    <div class="p-2 border-bottom">
                    <h4><i class="fas fa-server"></i></h4>
                    1/4 RACK</div>
                    <div class="p-2 border-bottom">
                        <small>Network 1 Gbps</small><br>
                        <small class="text-muted">10/10 Mbps Inter</small><br>
                        <small>10 Public IP</small><br>
                        <small>Power 4 AMP</small><br>
                        <small>Free Firewall</small>
                    </div>
                    <div class="p-2">
                        12,000 THB/Month
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="card text-center mb-3">
                    <div class="p-2 border-bottom">
                    <h4><i class="fas fa-server"></i></h4>
                    1/2 RACK</div>
                    <div class="p-2 border-bottom">
                        <small>Network 1 Gbps</small><br>
                        <small class="text-muted">10/10 Mbps Inter</small><br>
                        <small>20 Public IP</small><br>
                        <small>Power 8 AMP</small><br>
                        <small>Free Firewall</small>
                    </div>
                    <div class="p-2">
                        22,000 THB/Month
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="card text-center mb-3">
                    <div class="p-2 border-bottom">
                    <h4><i class="fas fa-server"></i></h4>
                    FULL RACK</div>
                    <div class="p-2 border-bottom">
                        <small>Network 1 Gbps</small><br>
                        <small class="text-muted">10/10 Mbps Inter</small><br>
                        <small>40 Public IP</small><br>
                        <small>Power 16 AMP</small><br>
                        <small>Free Firewall</small>
                    </div>
                    <div class="p-2">
                        35,000 THB/Month
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5 pt-5 pb-5">
                <h3>ADDON</h3>
                <p>บริการเสริม</p>
        </div>
        <div class="row justify-content-center mt-5 mb-5">   
            <div class="col-lg-3">
                    <div class="card text-center mb-3">
                        <div class="p-2 border-bottom">
                        <h4><i class="fas fa-network-wired"></i></h4>
                        PUBLIC IP</div>
                        <div class="p-2 border-bottom">
                            <small>เพิ่มไอพีแอดแดรส</small>
                        </div>
                        <div class="p-2">
                            50 THB/IP
                        </div>
                    </div>
            </div>
            <div class="col-lg-3">
                    <div class="card text-center mb-3">
                        <div class="p-2 border-bottom">
                        <h4><i class="fas fa-globe-asia"></i></h4>
                        Network 1 Gbps</div>
                        <div class="p-2 border-bottom">
                            <small>เพิ่มเน็ตภายในประเทศ</small>
                        </div>
                        <div class="p-2">
                            2,500 THB
                        </div>
                    </div>
            </div>
            <div class="col-lg-3">
                    <div class="card text-center mb-3">
                        <div class="p-2 border-bottom">
                        <h4><i class="fas fa-plug"></i></h4>
                        Addon Power 1 AMP</div>
                        <div class="p-2 border-bottom">
                            <small>เพิ่มไฟ 1 AMP</small>
                        </div>
                        <div class="p-2">
                            1,000 THB
                        </div>
                    </div>
            </div>
        </div>
        <a href="?page=contact" target="_blank" class="btn btn-primary btn-block">ติดต่อเรา</a>
    </div>
</div>
</div>