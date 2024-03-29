<!doctype html>
<html lang="en">

<head>
  <title>ReHTTP</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/css/style.css">

  <link rel="icon" type="image/png" href="assets/images/favicon.png" />


</head>

<body>
<input type="hidden" id="cstoken" value="<?php echo $_SESSION['token'] ?? '' ?>">
  <section class="ftco-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 text-center mb-5">
          <h2 class="heading-section">ReHTTP</h2>
        </div>
      </div>
      <div class="input-group">
  <input type="text" class="form-control text-center" aria-label="Text input with dropdown button" placeholder="Default Show All Client" id="search">

</div>
<br>

      <div class="row">
        <div class="col-sm-4 col-md-12">
          <div class="table-wrap">
            <table class=" table table-responsive-xl ">
              <thead>
                <tr>
                  <th>&nbsp;</th>
                  <th onclick='$("input:checkbox").trigger("click")'>OS</th>
                  <th>IP</th>
                  <th>uuid</th>
                  <th>Username</th>
                  <th>Member</th>
                  <th>Country</th>                  
                  <th>Status</th>
                  <th>JobQueue</th>

                  <th>&nbsp;</th>
               
                </tr>
              </thead>
              <tbody id="client_area">
            

              </tbody>
              
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
