<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <?=$this->view("admin/menu");?>
        </div>
        <div class="col-md-9">
            <?=$this->view("admin/_includes/messages");?>
            <div class="row">
                <div class="col-sm-4">
                    <div class="card text-center">
                        <div class="card-header">Demo</div>
                        <div class="card-body">
                            <h5 class="card-title">0</h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card text-center">
                        <div class="card-header">Demo</div>
                        <div class="card-body">
                            <h5 class="card-title">0</h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card text-center">
                        <div class="card-header">Demo</div>
                        <div class="card-body">
                            <h5 class="card-title">0</h5>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-4">
                    <div class="card text-center">
                        <div class="card-header">Demo</div>
                        <div class="card-body">
                            <h5 class="card-title">0</h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card text-center">
                        <div class="card-header">Demo</div>
                        <div class="card-body">
                            <h5 class="card-title">0</h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card text-center">
                        <div class="card-header">Demo</div>
                        <div class="card-body">
                            <h5 class="card-title">0</h5>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-header">Demo Chart</div>
                        <div class="card-body">
                            <canvas id="chart1"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header">Demo Chart</div>
                        <div class="card-body">
                            <canvas id="chart2" height="330"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <br>
        </div>
    </div>
</div>

<script src="<?=asset("js/Chart.min.js")?>"></script>
<script>
    getChart1();
    getChart2();
    function getChart1() {
        var content = document.getElementById("chart1").getContext("2d");
        var chart = new Chart(content, {
            type:'line',
            data: {
                labels: ["A", "B", "C"],
                datasets: [{
                    label:"Demo 1",
                    backgroundColor:"#0000FF",
                    borderColor:"#0000FF",
                    data:[random(), random(), random()],
                    fill:false
                }, {
                    label:"Demo 2",
                    backgroundColor:"#FF0000",
                    borderColor:"#FF0000",
                    data:[random(), random(), random()],
                    fill:false
                }]
            }
        });
    }
    function getChart2() {
        var content = document.getElementById("chart2").getContext("2d");
        var chart = new Chart(content, {
            type:'pie',
            data: {
                labels: ["A","B","C"],
                datasets: [{
                    backgroundColor:["#FFCE56","#36A2EB","#FF6384"],
                    data:[random(), random(), random()],
                }]
            }
        });
    }
    function random() {
        return Math.floor(Math.random() * 100);
    }
</script>