<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="navbar-brand navbar-fixed-top" style="color: #ffffff">&nbsp;&nbsp;&nbsp; GE HOUSE KUORTANEENKATU 2</div>
        </div>

        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a style="color: #ffffff" href="#">
                        WELCOME <?php echo $username; ?>
                        <?php if($working_building != NULL): ?>
                        - Working building <?php echo $working_building; ?>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
            <!--<form class="navbar-form navbar-right">
                <input type="text" class="form-control" placeholder="Search...">
            </form>-->
        </div>
    </div>
</div>