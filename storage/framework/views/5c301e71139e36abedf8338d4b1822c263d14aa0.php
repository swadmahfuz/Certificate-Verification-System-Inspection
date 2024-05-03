<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Edit Certificate Details</title>
</head>
    <body background="../images/tuv-login-background1.jpg">
        <section style="padding-top: 60px;">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 offset-md-3">
                        <div class="card">
                            <div class="card-header" >
                                <center><h3>Edit Certificate Information</h3></center>
                                <center>
                                    <a href="../dashboard" class="btn btn-primary">Go back to Dashboard</a> 
                                    <a href="../delete-certificate/<?php echo e($certificate->id); ?>" class="btn btn-danger">Delete Certificate</a>
                                    <h6 style="text-align: right">* Required fields</h6>
                                </center> 
                            </div>
                            <div class="card-body">
                                <?php if(Session::has('Edited details successflly')): ?>
                                    <div class="alert alert-success" role="alert">
                                        <?php echo e(Session::get('Details_Edited')); ?>

                                    </div>
                                <?php endif; ?>
                                <form action="<?php echo e(route('certificate.update')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <input type="hidden" name="id" value="<?php echo e($certificate->id); ?>">

                                        <label for="certificate_number">Certificate Number*</label>
                                        <?php $__errorArgs = ['certificate_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger"><?php echo e($message); ?></span> <br> 
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <input type="text" name="certificate_number" class="form-control" placeholder="Enter Certificate Number" value="<?php echo e($certificate->certificate_number); ?>">
                                        <br>
                                        
                                        <label for="client_name">Client*</label>
                                        <?php $__errorArgs = ['client_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger"><?php echo e($message); ?></span> <br> 
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <input type="text" name="client_name" class="form-control" placeholder="Enter Client Name" value="<?php echo e($certificate->client_name); ?>">
                                        <br>
                                        
                                        <label for="inspection_type">Inspection Type*</label>
                                        <?php $__errorArgs = ['inspection_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger"><?php echo e($message); ?></span> <br> 
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <input type="text" name="inspection_type" class="form-control" placeholder="Enter Inspection Type" value="<?php echo e($certificate->inspection_type); ?>">
                                        <br>

                                        <label for="inspection_location">Inspection Location*</label>
                                        <?php $__errorArgs = ['inspection_location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger"><?php echo e($message); ?></span> <br> 
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <input type="text" name="inspection_location" class="form-control" placeholder="Enter Inspection Location" value="<?php echo e($certificate->inspection_location); ?>">
                                        <br>

                                        <label for="equipment_name">Equipment/Item*</label>
                                        <?php $__errorArgs = ['equipment_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger"><?php echo e($message); ?></span> <br> 
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <input type="text" name="equipment_name" class="form-control" placeholder="Enter Equipment/Item Name" value="<?php echo e($certificate->equipment_name); ?>">
                                        <br>

                                        <label for="equipment_brand">Equipment Brand/Manufacturer*</label>
                                        <?php $__errorArgs = ['equipment_brand'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger"><?php echo e($message); ?></span> <br> 
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <input type="text" name="equipment_brand" class="form-control" placeholder="Enter Item Manufacturer/Brand" value="<?php echo e($certificate->equipment_brand); ?>">
                                        <br>

                                        <label for="equipment_serial_chassis">Serial/Chassis No.*</label>
                                        <?php $__errorArgs = ['equipment_serial_chassis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger"><?php echo e($message); ?></span> <br> 
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <input type="text" name="equipment_serial_chassis" class="form-control" placeholder="Enter Serial/Chassis No." value="<?php echo e($certificate->equipment_serial_chassis); ?>">
                                        <br> 

                                        <label for="equipment_rated_capacity">Rated Capacity</label>
                                        <input type="text" name="equipment_rated_capacity" class="form-control" placeholder="Enter Rated Capacity" value="<?php echo e($certificate->equipment_rated_capacity); ?>">
                                        <br>
                                         

                                        <label for="equipment_swl">SWL</label>
                                        <input type="text" name="equipment_swl" class="form-control" placeholder="Enter Safe Working Load" value="<?php echo e($certificate->equipment_swl); ?>">
                                        <br>

                                        <label for="inspection_date">Inspection Date*</label>
                                        <?php $__errorArgs = ['inspection_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger"><?php echo e($message); ?></span> <br> 
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <input type="date" name="inspection_date" class="form-control" placeholder="Enter Inspection Date" value="<?php echo e($certificate->inspection_date); ?>">
                                        <br> 

                                        <label for="validity_date">Validity Expiration Date*</label>
                                        <?php $__errorArgs = ['validity_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger"><?php echo e($message); ?></span> <br> 
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <input type="date" name="validity_date" class="form-control" placeholder="Enter Certificate Expiration Date" value="<?php echo e($certificate->validity_date); ?>">
                                        <br>

                                        <label for="inspection_remarks">Inspection Notes</label>
                                        <textarea name="inspection_remarks" class="form-control" placeholder="Enter any inspection remarks." rows="4"><?php echo e($certificate->inspection_remarks); ?></textarea>
                                        <br>

                                        <label for="inspection_internal_notes">Inspection Notes</label>
                                        <textarea name="inspection_internal_notes" class="form-control" placeholder="Enter any inspection notes for internal use only." rows="4"><?php echo e($certificate->inspection_date); ?></textarea>
                                        <br>

                                        
                                        <center><button type="submit" class="btn btn-success">Update Certificate</button></center>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>
<?php /**PATH D:\xampp\htdocs\verify-cert-inspection\resources\views/edit-certificate.blade.php ENDPATH**/ ?>