<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            สินค้า
            <small>จัดการสินค้า</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo  base_url(); ?>"><i class="fa fa-dashboard"></i> หน้าแรก</a></li>
            <li class="active">สินค้า</li>
        </ol>
    </section>
    <!-- Top menu -->
    <?php echo $this->session->flashdata('msginfo'); ?>
    <!-- Main content -->
    <section class="content">
        <!-- Your Page Content Here -->
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">ตารางข้อมูล</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-sm-6">
                            <a class="btn btn-success" href="<?php echo  base_url('item/newdata'); ?>" role="button"><i class="fa fa-fw fa-plus-circle"></i> เพิ่มข้อมูล</a>
                            <a class="btn btn-default" href="<?php echo  base_url('item'); ?>" role="button"><i class="fa fa-fw fa-refresh"></i> Refresh Data</a>
                        </div>
                        <div class="col-sm-6">
                            <div id="" class="dataTables_filter">
                            <form action="" method="GET" name="search">
                            	<label>ค้นหา</label>:<input type="search" name="keyword" class="form-control input-sm" placeholder="ค้นหาชื่อสินค้า"></label>
                            </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" style="width: 5%;">#</th>
                                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" style="width: 30%;">ชื่อ</th>
                                        <th class="sorting" tabindex="0" rowspan="1" colspan="1" style="width: 30%">ราคา</th>
                                        <th class="sorting" tabindex="0" rowspan="1" colspan="1" style="width: 10%">หน่วยนับ</th>
                                        <th class="sorting" tabindex="0" rowspan="1" colspan="1" style="width:  60px;">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($results)){ foreach ($results as $data) { ?>
                                        <tr role="row">
                                            <td><img src="<?php echo base_url() . 'pictures/sm_' . $data->filename;?>" alt=""></td>
                                            <td>
                                            <a href="<?php echo base_url('item/edit/'.$data->id); ?>"><?php echo  $data->name; ?></a>
                                            </td>
                                            <td><?php echo number_format($data->price,2); ?></td>
                                            <td><?php echo $data->unit; ?></td>
                                            <td>
                                            	<a class="btn btn-danger btn-xs deleteLine" href="<?php echo  base_url('item/remove/'.$data->id); ?>" role="button"><i class="fa fa-fw fa-trash"></i> ลบข้อมูล</a>
                                            </td>
                                        </tr>
                                    <?php } } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Total <?php echo  $total_rows; ?> entries</div>
                        </div>
                        <div class="col-sm-7">
                            <div id="example1_paginate" class="dataTables_paginate paging_simple_numbers">
                                <?php echo $link; ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div><!-- /.box-body -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
$(document).ready(function(){
    $('.deleteLine').click(function(){
       if(!confirm("ยืนยันการลบข้อมูล?")){
           return false;
       } 
    });
});  
</script>