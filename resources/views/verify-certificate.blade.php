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
      href={{ URL::asset('public/main.css'); }} 
      rel="stylesheet"
      type="text/css"
    />
    <link rel="icon" href="#" sizes="32x32">
    <link rel="icon" href="#" sizes="192x192">
    <link rel="apple-touch-icon" href="#">
  </head>
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
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
      @isset($certificates)
      <div style="margin-right: 100px;">
      @if($certificates->count() < 1)
        <h3 style="text-align: center;">⚠️ The Certificate You Entered is Invalid or Manipulated. Please contact TUV Austria for futher inquiry. ⚠️</h3>
        </br>
        <h3 style="text-align: center;">⚠️ Tel: +88 02 8836403 ; Email: info@tuvat.com.bd ⚠️</h3>
      @endif
      @foreach ($certificates as $certificate)

        <div style="padding-left: 10px; padding-right: 10px;">
              @if (empty($certificate->validity_date) || ! \Carbon\Carbon::parse($certificate->validity_date)->isPast())
                  <h3 style="color: green; text-align: center;">Certificate Authentic and Valid! ✅</h3>
              @else
                  <h3 style="color: red; text-align: center;">Certificate Authentic but Expired! ⚠️</h3>
              @endif
              <br>
              <table style="width: 100%; border-collapse: collapse;">
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Certificate Number</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3>{{ $certificate->certificate_number }}</h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Client</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3>{{ $certificate->client_name }}</h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Inspection Type</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3>{{ $certificate->inspection_type }}</h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Inspection Location</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3>{{ $certificate->inspection_location }}</h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Equipment/Item</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3>{{ $certificate->equipment_name }}</h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Manufacturer</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3>{{ $certificate->equipment_brand }}</h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Serial/Chassis No.</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3>{{ $certificate->equipment_serial_chassis }}</h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Rated Capacity</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3>{{ $certificate->equipment_rated_capacity }}</h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Safe Working Load</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3>{{ $certificate->equipment_swl }}</h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Inspection Date</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;"><h3>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $certificate->inspection_date)->format('d M Y') }}</h3></td>
                  </tr>
                  <tr>
                      <td style="padding: 6px;"><h3><strong>Valid till</strong></h3></td>
                      <td style="padding: 6px;"><h3>:</h3></td>
                      <td style="padding: 6px;">
                        <h3>
                            @if (!empty($certificate->validity_date))
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $certificate->validity_date)->format('d M Y') }}
                            @else
                                No Expiry Date
                            @endif
                        </h3>
                      </td>
                  </tr>
              </table>
        </div>
      
      @endforeach
      </div>
      @endisset
    </div>
    @include('layouts.footer')  <!-- Including the footer Blade file -->
  </body>
</html>
