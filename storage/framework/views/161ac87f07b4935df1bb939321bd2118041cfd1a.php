<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <style>
      .wf-force-outline-none[tabindex="-1"]:focus {
        outline: none;
      }
    </style>
    <meta charset="utf-8" />
    <title>Certificate Verification</title>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link
      href=<?php echo e(URL::asset('public/main.css')); ?> 
      rel="stylesheet"
      type="text/css"
    />
    <link rel="icon" href="#" sizes="32x32">
    <link rel="icon" href="#" sizes="192x192">
    <link rel="apple-touch-icon" href="#">
  </head>
  <body>
    <div class="section wf-section">
      <img
        src="images/TUV Austria Logo.png"
        loading="lazy"
        alt=""
        class="image"
        width="250"
      />
      <h1 class="heading">Verify Inspection Certificate</h1>
      <p class="paragraph">
        Enter the Certificate Number and click the "Verify"&nbsp;button.
      </p>
      <div class="form-block w-form">
        <form
          id="s-form"
          name="s-form"
          method="get"
          class="form"
          aria-label="Search Form"
        >
          <input
            type="text"
            class="text-field w-input"
            maxlength="256"
            name="search"
            placeholder="Ex: TUV/CERT/2022/0911/001"
            id="search"
            required=""
          /><input
            type="submit"
            value="VERIFY"
            data-wait="Please wait..."
            class="submit-button w-button"
          />
        </form>
        <div
          class="w-form-done"
          tabindex="-1"
          role="region"
          aria-label="Form success"
        >
          <div>Thank you! Your submission has been received!</div>
        </div>
        <div
          class="w-form-fail"
          tabindex="-1"
          role="region"
          aria-label="Email Form failure"
        >
          <div>Oops! Something went wrong while submitting the form.</div>
        </div>
      </div>
      <?php if(isset($certificates)): ?>
      <div style="margin-right: 100px;">
      <?php if($certificates->count() < 1): ?>
        <h3>❌ The Certificate You Entered is Invalid or Manipulated. Please contact TUV Austria for futher inquiry. ❌</h3>
      <?php endif; ?>
      <?php $__currentLoopData = $certificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certificate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <div>
              <h3>Certificate Authentic! ✅</h3>
              <br>
              <table style="width: 100%; border-collapse: collapse;">
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Certificate Number</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3><?php echo e($certificate->certificate_number); ?></h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Client</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3><?php echo e($certificate->client_name); ?></h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Inspection Type</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3><?php echo e($certificate->inspection_type); ?></h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Inspection Location</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3><?php echo e($certificate->inspection_location); ?></h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Equipment/Item</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3><?php echo e($certificate->equipment_name); ?></h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Manufacturer</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3><?php echo e($certificate->equipment_brand); ?></h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Serial/Chassis No.</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3><?php echo e($certificate->equipment_serial_chassis); ?></h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Rated Capacity</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3><?php echo e($certificate->equipment_rated_capacity); ?></h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Safe Working Load</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3><?php echo e($certificate->equipment_swl); ?></h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Inspection Date</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3><?php echo e($certificate->inspection_date); ?></h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Valid till</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3><?php echo e($certificate->validity_date); ?></h3></td>
                  </tr>
              </table>
        </div>
      
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
      <?php endif; ?>
    </div>
  </body>
</html>
<?php /**PATH D:\xampp\htdocs\verify-cert-inspection\resources\views/verify.blade.php ENDPATH**/ ?>