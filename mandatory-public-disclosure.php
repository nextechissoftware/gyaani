<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Gyaana International School</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/logo.jpeg" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
        --navy:    #1e3a6e;
        --blue:    #2b5ea7;
        --green:   #2e7d52;
        --teal:    #1e8a7a;
        --red:     #e02020;
        --link:    #1a78c2;
        --light-bg:#f5f7fa;
        --border:  #dde3ed;
        --text:    #222;
        --muted:   #555;
        }

        /* ── PAGE HEADER ── */
        .page-hero {
        background: linear-gradient(135deg, #e8f0fb 0%, #f0f8ff 60%, #e6f4f1 100%);
        text-align: center;
        padding: 48px 20px 40px;
        border-bottom: 1px solid var(--border);
        }
        .page-hero .school-label {
        font-family: 'Rajdhani', sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 3px;
        color: var(--red);
        text-transform: uppercase;
        margin-bottom: 10px;
        }
        .page-hero h1 {
        font-family: 'Rajdhani', sans-serif;
        font-size: clamp(2rem, 5vw, 3.2rem);
        font-weight: 700;
        color: var(--text);
        letter-spacing: 1px;
        }

        /* ── WRAPPER ── */
        .container {
        max-width: 1180px;
        margin: 0 auto;
        padding: 40px 24px 60px;
        }

        /* ── SECTION HEADINGS ── */
        .section-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.45rem;
        font-weight: 700;
        color: var(--navy);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 40px 0 14px;
        padding-left: 4px;
        border-left: 4px solid var(--blue);
        padding-left: 12px;
        }

        /* ── TABLES ── */
        .disc-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid var(--border);
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 32px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        /* header rows */
        .disc-table thead tr {
        color: #fff;
        }
        .disc-table.blue-head thead tr   { background: var(--navy); }
        .disc-table.green-head thead tr  { background: var(--green); }
        .disc-table.teal-head thead tr   { background: var(--teal); }

        .disc-table thead th {
        padding: 13px 18px;
        text-align: left;
        font-weight: 700;
        font-size: 14px;
        letter-spacing: 0.4px;
        }

        /* body rows */
        .disc-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background 0.15s;
        }
        .disc-table tbody tr:last-child { border-bottom: none; }
        .disc-table tbody tr:hover { background: #f0f5ff; }

        .disc-table tbody td {
        padding: 13px 18px;
        vertical-align: top;
        color: var(--text);
        font-size: 14.5px;
        }
        .disc-table tbody td:first-child {
        color: var(--muted);
        font-weight: 600;
        width: 80px;
        text-align: center;
        }

        /* links */
        .disc-table a {
        color: var(--link);
        text-decoration: none;
        font-weight: 600;
        }
        .disc-table a:hover { text-decoration: underline; }

        /* blank dash */
        .blank { color: #aaa; }

        /* sub-list inside a cell */
        .sub-list { list-style: none; padding: 0; }
        .sub-list li { padding: 2px 0; }
        .sub-list li strong { font-weight: 700; }

        /* col widths helpers */
        .col-sl   { width: 70px; }
        .col-info { width: 45%; }
        .col-det  { }
        .col-upload { width: 160px; text-align: right !important; }
    </style>
</head>
<body>
    <!-- Spinner Start -->
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="index.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary">
                <img src="img/logo.jpeg" alt="logo" height="50" width="55">
                <span style="font-weight:700;"><span style="color:#9B3FAF;">G</span><span style="color:#F4C20D;">y</span><span style="color:#E53935;">a</span><span style="color:#1976D2;">a</span><span style="color:#29B6F6;">n</span><span style="color:#F9A825;">a</span></span>
                <span class="text-dark">International School</span>
                </h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.php" class="nav-item nav-link">Home</a>
                <a href="about.php" class="nav-item nav-link">About</a>
                <a href="team.php" class="nav-item nav-link">Our Team</a>
                <a href="gallery.php" class="nav-item nav-link">Gallery</a>
                <a href="contact.php" class="nav-item nav-link">Contact</a>
                <a href="mandatory-public-disclosure.php" class="nav-item nav-link active">Mandatory Public Disclosure</a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->
<!-- PAGE HERO -->
<div class="page-hero">
  <h1>Mandatory Public Disclosure</h1>
</div>

<div class="container">

  <!-- ══ TABLE A: GENERAL INFORMATION ══ -->
  <table class="disc-table blue-head">
    <thead>
      <tr>
        <th class="col-sl">SL</th>
        <th class="col-info">Information</th>
        <th class="col-det">Details</th>
      </tr>
    </thead>
    <tbody>
      <tr><td>1</td><td>Name of the School</td><td>Gyaana International School</td></tr>
      <tr><td>2</td><td>Affiliation No. (If Applicable)</td><td><span>2134561</span></td></tr>
      <tr><td>3</td><td>School Code (If Applicable)</td><td><span>72403</span></td></tr>
      <tr><td>4</td><td>Complete Address with Pin Code</td><td>Kahli, Gausganj, Hardoi, UTTAR PRADESH - 273202</td></tr>
      <tr><td>5</td><td>Principal Name &amp; Qualification</td><td>Mr. Gaurav Kumar Agarwal</td></tr>
      <tr><td>6</td><td>School Email ID</td><td>principal@gyaanainternationalschool.com</td></tr>
      <tr><td>7</td><td>Contact Details (Landline / Mobile)</td><td>+91 7522852280</td></tr>
    </tbody>
  </table>

  <!-- ══ TABLE B: DOCUMENTS ══ -->
  <table class="disc-table blue-head">
    <thead>
      <tr>
        <th class="col-sl">SL No.</th>
        <th class="col-info">Documents / Information</th>
        <th class="col-upload">Upload Documents</th>
      </tr>
    </thead>
    <tbody>
      <tr><td>1</td><td>Copies of Affiliation / Upgradation Letter and Recent Extension of Affiliation, if any</td><td style="text-align:right"><a href="mandatory-public-disclosure/affiliation_letter.pdf">Click to View</a></td></tr>
      <tr><td>2</td><td>Copies of Societies / Trust / Company Registration / Renewal Certificate, as applicable</td><td style="text-align:right"><a href="mandatory-public-disclosure/Society.pdf">Click to View</a></td></tr>
      <tr><td>3</td><td>Copy of No Objection Certificate (NOC) issued, if applicable, by the State Govt. / UT</td><td style="text-align:right"><span class="blank">——————————</span></td></tr>
      <tr><td>4</td><td>Copies of Recognition Certificate under RTE Act, 2009 and its Renewal, if applicable</td><td style="text-align:right"><a href="mandatory-public-disclosure/BSA.pdf">Click to View</a></td></tr>
      <tr><td>5</td><td>Copy of Valid Building Safety Certificate as per the National Building Code</td><td style="text-align:right"><a href="mandatory-public-disclosure/building safety certificate.pdf">Click to View</a></td></tr>
      <tr><td>6</td><td>Copy of Valid Fire Safety Certificate issued by the Competent Authority</td><td style="text-align:right"><a href="mandatory-public-disclosure/fire.pdf">Click to View</a></td></tr>
      <tr><td>7</td><td>Copy of Water Health and Sanitation Certificate</td><td style="text-align:right"><a href="mandatory-public-disclosure/water_and_sanitation.pdf">Click to View</a></td></tr>
    </tbody>
  </table>

  <!-- ══ TABLE C: SCHOOL DOCUMENTS ══ -->
  <table class="disc-table green-head">
    <thead>
      <tr>
        <th class="col-sl">S. No.</th>
        <th class="col-info">Documents / Information</th>
        <th class="col-upload">Upload Documents</th>
      </tr>
    </thead>
    <tbody>
      <tr><td>1</td><td>Fee Structure of the School</td><td style="text-align:right"><a href="mandatory-public-disclosure/fee_structure.pdf">Click to View</a></td></tr>
      <tr><td>2</td><td>Annual Academic Calendar</td><td style="text-align:right"><a href="mandatory-public-disclosure/academic_calendar.pdf">Click to View</a></td></tr>
      <tr><td>3</td><td>List of School Management Committee (SMC)</td><td style="text-align:right"><a href="mandatory-public-disclosure/SMC.pdf">Click to View</a></td></tr>
      <tr><td>4</td><td>List of Parents Teachers Association (PTA) Members</td><td style="text-align:right"><a href="mandatory-public-disclosure/pta.pdf">Click to View</a></td></tr>
      <tr><td>5</td><td>Last Three-Year Result of the Board Examination (as per applicability)</td><td style="text-align:right"><span class="blank">——————————</span></td></tr>
    </tbody>
  </table>

  <!-- ══ RESULT CLASS X ══ -->
  <h2>Result – Class X</h2>
  <table class="disc-table blue-head">
    <thead>
      <tr>
        <th class="col-sl">S. No.</th>
        <th>Year</th>
        <th>No. of Registered Students</th>
        <th>No. of Students Passed</th>
        <th>Pass Percentage</th>
        <th>Remarks</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td><span class="blank">——</span></td>
        <td><span class="blank">——</span></td>
        <td><span class="blank">——</span></td>
        <td><span class="blank">——%</span></td>
        <td><span class="blank">——</span></td>
      </tr>
    </tbody>
  </table>

  <!-- ══ RESULT CLASS XII ══ -->
  <h2>Result – Class XII</h2>
  <table class="disc-table green-head">
    <thead>
      <tr>
        <th class="col-sl">S. No.</th>
        <th>Year</th>
        <th>No. of Registered Students</th>
        <th>No. of Students Passed</th>
        <th>Pass Percentage</th>
        <th>Remarks</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td><span class="blank">——</span></td>
        <td><span class="blank">——</span></td>
        <td><span class="blank">——</span></td>
        <td><span class="blank">——%</span></td>
        <td><span class="blank">——</span></td>
      </tr>
    </tbody>
  </table>

  <!-- ══ D: STAFF (TEACHING) ══ -->
  <h2>D: Staff (Teaching)</h2>
  <table class="disc-table blue-head">
    <thead>
      <tr>
        <th class="col-sl">S. No.</th>
        <th class="col-info">Information</th>
        <th>Number / Strength / Name &amp; Qualifications</th>
      </tr>
    </thead>
    <tbody>
      <tr><td>1</td><td>Principal</td><td>01</td></tr>
      <tr><td>2</td><td>Vice Principal</td><td><span class="blank">——</span></td></tr>
      <tr><td>3</td><td>Headmistress / Headmaster</td><td><span>01</span></td></tr>
      <tr>
        <td>4</td>
        <td>
          Total No. of Teachers<br><br>
          <ul class="sub-list">
            <li><strong>PGT</strong> – Post Graduate Teachers</li>
            <li><strong>TGT</strong> – Trained Graduate Teachers</li>
            <li><strong>PRT</strong> – Primary Teachers</li>
          </ul>
        </td>
        <td>
          <strong>Total Teachers: 19</strong><br><br>
          <ul class="sub-list">
            <li>PGT – 05</li>
            <li>TGT – 08</li>
            <li>PRT – 06</li>
          </ul>
        </td>
      </tr>
      <tr><td>5</td><td>Teacher–Section Ratio</td><td>1.5 : 1</td></tr>
      <tr><td>6</td><td>Details of Special Educator</td><td>1</td></tr>
      <tr><td>7</td><td>Details of Counsellor &amp; Wellness Teacher</td><td>1</td></tr>
    </tbody>
  </table>

  <!-- ══ E: SCHOOL INFRASTRUCTURE ══ -->
  <h2>E: School Infrastructure</h2>
  <table class="disc-table teal-head">
    <thead>
      <tr>
        <th class="col-sl">S. No.</th>
        <th class="col-info">Information</th>
        <th>Details</th>
      </tr>
    </thead>
    <tbody>
      <tr><td>1</td><td>Total Campus Area of the School (in Sq. Mtr.)</td><td>6226 Sq. Mtr.</td></tr>
      <tr><td>2</td><td>No. and Size of the Classrooms (in Sq. Mtr.)</td><td>No.: 15 / Size: 48 Sq. Mtr.</td></tr>
      <tr><td>3</td><td>No. and Size of Laboratories including Computer Labs (in Sq. Mtr.)</td><td>No.: 06 / Size: 56 Sq. Mtr.</td></tr>
      <!-- <tr><td>4</td><td>No. and Size of Library (in Sq. Mtr.)</td><td>No.: 01 / Size: 112 Sq. Mtr.</td></tr> -->
      <tr><td>5</td><td>Internet Facility</td><td>Yes</td></tr>
      <tr><td>6</td><td>No. of Girls Toilets</td><td>08</td></tr>
      <tr><td>7</td><td>No. of Boys Toilets</td><td>08</td></tr>
      <!-- <tr><td>8</td><td>No. of CWSN Toilets</td><td>02</td></tr> -->
      <tr><td>9</td><td>Link of YouTube Video of the Inspection of School covering the Infrastructure of the School</td><td><a href="https://youtu.be/DVeLHMyhV2E?si=olmdVu_JMLSjXjZ6">View Video</a></td></tr>
    </tbody>
  </table>

</div><!-- /container -->
</body>
</html>