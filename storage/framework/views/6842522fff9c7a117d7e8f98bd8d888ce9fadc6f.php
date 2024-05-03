<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Add Certificate</title>
</head>
    <body background="images/tuv-login-background1.jpg">
        <section style="padding-top: 60px;">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 offset-md-3">
                        <div class="card">
                            <div class="card-header" >
                                <center><h3>Add Certificate Information</h3></center>
                                <center>
                                    <a href="./dashboard" class="btn btn-primary">Go back to Dashboard</a> 
                                    <h6 style="text-align: right">* Required fields</h6>
                                </center> 
                            </div>
                            <div class="card-body">
                                
                                <form class="col s12" method="POST" action="<?php echo e(route('certificate.create')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        
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
                                        <input type="text" name="certificate_number" class="form-control" placeholder="Enter Certificate Number" value="">
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
                                        <input type="text" name="client_name" class="form-control" placeholder="Enter Client Name" value="<?php echo e(old('client_name')); ?>">
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
                                        <input type="text" name="inspection_type" class="form-control" placeholder="Enter Inspection Type" value="<?php echo e(old('inspection_type')); ?>">
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
                                        <input type="text" name="inspection_location" class="form-control" placeholder="Enter Inspection Location" value="<?php echo e(old('inspection_location')); ?>">
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
                                        <input type="text" name="equipment_name" class="form-control" placeholder="Enter Equipment/Item Name" value="<?php echo e(old('equipment_name')); ?>">
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
                                        <input type="text" name="equipment_brand" class="form-control" placeholder="Enter Item Manufacturer/Brand" value="<?php echo e(old('equipment_brand')); ?>">
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
                                        <input type="text" name="equipment_serial_chassis" class="form-control" placeholder="Enter Serial/Chassis No." value="<?php echo e(old('equipment_serial_chassis')); ?>">
                                        <br> 

                                        <label for="equipment_rated_capacity">Rated Capacity</label>
                                        <input type="text" name="equipment_rated_capacity" class="form-control" placeholder="Enter Rated Capacity" value="<?php echo e(old('equipment_rated_capacity')); ?>">
                                        <br>
                                         

                                        <label for="equipment_swl">SWL</label>
                                        <input type="text" name="equipment_swl" class="form-control" placeholder="Enter Safe Working Load" value="<?php echo e(old('equipment_swl')); ?>">
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
                                        <input type="date" name="inspection_date" class="form-control" placeholder="Enter Inspection Date" value="<?php echo e(old('inspection_date')); ?>">
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
                                        <input type="date" name="validity_date" class="form-control" placeholder="Enter Certificate Expiration Date" value="<?php echo e(old('validity_date')); ?>">
                                        <br>

                                        <label for="inspection_remarks">Inspection Notes</label>
                                        <textarea name="inspection_remarks" class="form-control" placeholder="Enter any inspection remarks." rows="4"><?php echo e(old('inspection_remarks')); ?></textarea>
                                        <br>

                                        <label for="inspection_internal_notes">Inspection Notes</label>
                                        <textarea name="inspection_internal_notes" class="form-control" placeholder="Enter any inspection notes for internal use only." rows="4"><?php echo e(old('inspection_internal_notes')); ?></textarea>
                                        <br>

                                        
                                        <center><button type="submit" class="btn btn-success">Add Details</button></center>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>
<?php /**PATH D:\xampp\htdocs\verify-cert-inspection\resources\views/add-certificate.blade.php ENDPATH**/ ?>