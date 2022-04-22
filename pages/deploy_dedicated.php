<?php
    if(empty($_SESSION['username'])){
        die('<script>window.location.replace("?page=home")</script>');
    }

?>
<div class="container pb-5">
<div class="card">
    <div class="card-body">
        <h4><i class="fas fa-server"></i> <?= L::dedicated?></h4>
        <hr>
        <div class="py-2 text-center mt-5 pt-5 pb-5">
                <h3>Dedicated</h3>
                <p>เช่าเครื่องเซิร์ฟเวอร์</p>
        </div>
        <div class="row mt-5 mb-5 justify-content-center">
        <div class="col-lg-4">
            <div class="card text-center mb-3">
                <div class="p-2 border-bottom">
                <h4><i class="fas fa-server"></i></h4>
                Dell R210</div>
                <div class="p-2 border-bottom">
                    <small>Intel Xeon X3430</small><br>
                    <small>Speed 2.40 GHz</small><br>
                    <small>Ram DDR3 8 GB</small><br>
                    <small>SAS 146 GB</small>
                </div>
                <div class="p-2">
                    1,000 THB/Month
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card text-center mb-3">
                <div class="p-2 border-bottom">
                <h4><i class="fas fa-server"></i></h4>
                Dell R210 II</div>
                <div class="p-2 border-bottom">
                    <small>Intel Xeon E3-1220</small><br>
                    <small>Speed 3.10 GHz</small><br>
                    <small>Ram DDR3 8 GB</small><br>
                    <small>SAS 146 GB</small>
                </div>
                <div class="p-2">
                    1,500 THB/Month
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card text-center mb-3">
                <div class="p-2 border-bottom">
                <h4><i class="fas fa-server"></i></h4>
                Dell R210 II</div>
                <div class="p-2 border-bottom">
                    <small>Intel Xeon E3-1240 V2</small><br>
                    <small>Speed 3.40 GHz</small><br>
                    <small>Ram DDR3 8 GB</small><br>
                    <small>SAS 146 GB</small>
                </div>
                <div class="p-2">
                    2,000 THB/Month
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card text-center mb-3">
                <div class="p-2 border-bottom">
                <h4><i class="fas fa-server"></i></h4>
                Dell R220</div>
                <div class="p-2 border-bottom">
                    <small>Intel Xeon E3-1220 V3</small><br>
                    <small>Speed 3.10 GHz</small><br>
                    <small>Ram DDR3 8 GB</small><br>
                    <small>SAS 146 GB</small>
                </div>
                <div class="p-2">
                    2,500 THB/Month
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card text-center mb-3">
                <div class="p-2 border-bottom">
                <h4><i class="fas fa-server"></i></h4>
                Dell M610</div>
                <div class="p-2 border-bottom">
                    <small>Intel Xeon E5620</small><br>
                    <small>Speed 2.40 GHz</small><br>
                    <small>Ram DDR3 8 GB</small><br>
                    <small>SAS 146 GB</small>
                </div>
                <div class="p-2">
                    2,500 THB/Month
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card text-center mb-3">
                <div class="p-2 border-bottom">
                <h4><i class="fas fa-server"></i></h4>
                Dell M610</div>
                <div class="p-2 border-bottom">
                    <small>Intel Xeon X5650</small><br>
                    <small>Speed 2.66 GHz</small><br>
                    <small>Ram DDR3 8 GB</small><br>
                    <small>SAS 146 GB</small>
                </div>
                <div class="p-2">
                    3,000 THB/Month
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card text-center mb-3">
                <div class="p-2 border-bottom">
                <h4><i class="fas fa-server"></i></h4>
                Dell M610</div>
                <div class="p-2 border-bottom">
                    <small>Intel Xeon X5670</small><br>
                    <small>Speed 2.93 GHz</small><br>
                    <small>Ram DDR3 8 GB</small><br>
                    <small>SAS 146 GB</small>
                </div>
                <div class="p-2">
                    3,500 THB/Month
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card text-center mb-3">
                <div class="p-2 border-bottom">
                <h4><i class="fas fa-server"></i></h4>
                Dell R610</div>
                <div class="p-2 border-bottom">
                    <small>Intel Xeon X5687</small><br>
                    <small>Speed 3.6 GHz</small><br>
                    <small>Ram DDR3 8 GB</small><br>
                    <small>SAS 146 GB</small>
                </div>
                <div class="p-2">
                    4,000 THB/Month
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card text-center mb-3">
                <div class="p-2 border-bottom">
                <h4><i class="fas fa-server"></i></h4>
                Dell R610</div>
                <div class="p-2 border-bottom">
                    <small>Intel Xeon X5690</small><br>
                    <small>Speed 3.46 GHz</small><br>
                    <small>Ram DDR3 8 GB</small><br>
                    <small>SAS 146 GB</small>
                </div>
                <div class="p-2">
                    4,500 THB/Month
                </div>
            </div>
        </div>
        </div>
        <div class="text-center mt-5 pt-5 pb-5">
                <h3>ADDON</h3>
                <p>บริการเสริม</p>
        </div>
        <div class="row justify-content-center mt-5 mb-5">   
            <div class="col-lg-4">
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
            <div class="col-lg-4">
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
            <div class="col-lg-4">
                    <div class="card text-center mb-3">
                        <div class="p-2 border-bottom">
                        <h4><i class="fas fa-memory"></i></h4>
                        Addon Ram 8 GB</div>
                        <div class="p-2 border-bottom">
                            <small>เพิ่มแรม 8 GB</small>
                        </div>
                        <div class="p-2">
                            500 THB
                        </div>
                    </div>
            </div>
        </div>
        <a href="?page=contact" target="_blank" class="btn btn-primary btn-block">ติดต่อเรา</a>
    </div>
</div>       
</div>          