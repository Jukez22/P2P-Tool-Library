<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Librarian Dashboard – 3EDTAK</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    .sidebar { min-height: calc(100vh - 56px); }
    .panel { display:none; }
    .panel.active { display:block; }
    .sidebar .btn { font-size:0.82rem; text-align:left; border-radius:0; }
    .border-secondary-subtle { border-color: #dee2e6 !important; }
  </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-light bg-white border-bottom">
  <div class="container-fluid px-3">
    <a class="navbar-brand fw-bold text-primary" href="index.html">3EDTAK - Librarian Dashboard</a>
    <div class="d-flex align-items-center gap-2">
      <small class="text-secondary">Welcome Back, Omar!</small>
      <span class="badge bg-danger">3 alerts</span>
      <a href="login.html" class="btn btn-outline-secondary btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">

    <div class="col-md-2 bg-white border-end sidebar p-0">
      <div class="p-3 border-bottom">
        <div class="fw-bold small text-dark">Omar Yehia</div>
        <div class="text-secondary" style="font-size:0.75rem;">Librarian · Zone: October</div>
      </div>
      <div class="p-1">
        <div class="text-secondary px-2 mt-2 mb-1" style="font-size:0.68rem;text-transform:uppercase;">Overview</div>
        <button class="btn btn-light w-100 text-dark" onclick="show('dashboard',this)">🏠 Dashboard</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('activity',this)">📊 Activity Monitor</button>

        <div class="text-secondary px-2 mt-2 mb-1" style="font-size:0.68rem;text-transform:uppercase;">Tools</div>
        <button class="btn btn-light w-100 text-secondary" onclick="show('audit',this)">🔍 Inventory Audit</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('pending',this)">⏳ Pending Approvals</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('qr',this)">📱 QR Handover</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('taxonomy',this)">🗂️ Taxonomy & Categories</button>

        <div class="text-secondary px-2 mt-2 mb-1" style="font-size:0.68rem;text-transform:uppercase;">Operations</div>
        <button class="btn btn-light w-100 text-secondary" onclick="show('disputes',this)">⚖️ Dispute Mediation</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('late',this)">⚠️ Late Returns</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('blacklist',this)">🚫 Blacklist Manager</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('insurance',this)">🛡️ Insurance Claims</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('refund',this)">↩️ Refund & Credit</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('assignment',this)">👥 Librarian Assignment</button>

        <div class="text-secondary px-2 mt-2 mb-1" style="font-size:0.68rem;text-transform:uppercase;">Finance & Admin</div>
        <button class="btn btn-light w-100 text-secondary" onclick="show('revenue',this)">💵 Revenue Reports</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('promotions',this)">🎟️ Promotions</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('zones',this)">🗺️ Zone Management</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('broadcast',this)">📢 Broadcast</button>
        <a href="login.html" class="btn btn-light w-100 text-secondary">🚪 Log Out</a>
      </div>
    </div>

    <div class="col-md-10 p-4">

      <div class="panel active" id="panel-dashboard">
        <div class="fw-bold fs-5 mb-1">Librarian Dashboard</div>
        <div class="text-secondary small mb-3">Platform overview – Giza October Zone</div>
        <div class="row g-3 mb-4">
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-4 fw-bold text-primary">84</div><small class="text-secondary">Active Rentals</small></div></div>
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-4 fw-bold text-danger">7</div><small class="text-secondary">Open Disputes</small></div></div>
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-4 fw-bold text-warning">3</div><small class="text-secondary">Late Returns</small></div></div>
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-4 fw-bold text-primary">12</div><small class="text-secondary">Pending Approvals</small></div></div>
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <div class="card bg-white border">
              <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold small">Recent Disputes</span>
                <button class="btn btn-outline-secondary btn-sm" onclick="show('disputes',null)">View All</button>
              </div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Case</th><th>Tool</th><th>Status</th><th>Action</th></tr></thead>
                  <tbody>
                    <tr><td>#1042</td><td>Power Drill</td><td><span class="badge bg-warning text-dark">Open</span></td><td><button class="btn btn-primary btn-sm" onclick="show('disputes',null)">Review</button></td></tr>
                    <tr><td>#1039</td><td>3D Printer</td><td><span class="badge bg-warning text-dark">Open</span></td><td><button class="btn btn-primary btn-sm" onclick="show('disputes',null)">Review</button></td></tr>
                    <tr><td>#1035</td><td>Tile Saw</td><td><span class="badge bg-success">Resolved</span></td><td><button class="btn btn-outline-secondary btn-sm">View</button></td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card bg-white border">
              <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold small">Late Returns</span>
                <button class="btn btn-outline-secondary btn-sm" onclick="show('late',null)">View All</button>
              </div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Member</th><th>Tool</th><th>Days Late</th><th>Action</th></tr></thead>
                  <tbody>
                    <tr><td>Karim A.</td><td>Milwaukee Drill</td><td><span class="badge bg-danger">2 days</span></td><td><button class="btn btn-danger btn-sm">Escalate</button></td></tr>
                    <tr><td>Rania M.</td><td>Tile Saw</td><td><span class="badge bg-warning text-dark">1 day</span></td><td><button class="btn btn-outline-secondary btn-sm">Notify</button></td></tr>
                    <tr><td>Hassan T.</td><td>Laser Level</td><td><span class="badge bg-danger">4 days</span></td><td><button class="btn btn-danger btn-sm">Escalate</button></td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Pending Tool Approvals</div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Tool</th><th>Submitted By</th><th>Date</th><th>Category</th><th>Actions</th></tr></thead>
                  <tbody>
                    <tr><td>🔨 Bosch Hammer Drill</td><td>Sara M.</td><td>May 2</td><td>Power Tools</td><td><button class="btn btn-success btn-sm me-1">Approve</button><button class="btn btn-danger btn-sm">Reject</button></td></tr>
                    <tr><td>📐 Laser Distance Meter</td><td>Ali K.</td><td>May 1</td><td>Measurement</td><td><button class="btn btn-success btn-sm me-1">Approve</button><button class="btn btn-danger btn-sm">Reject</button></td></tr>
                    <tr><td>🧰 Socket Wrench Set</td><td>Mona H.</td><td>Apr 30</td><td>Hand Tools</td><td><button class="btn btn-success btn-sm me-1">Approve</button><button class="btn btn-danger btn-sm">Reject</button></td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-activity">
        <div class="fw-bold fs-5 mb-1">Activity Monitor</div>
        <div class="text-secondary small mb-3">Real-time overview of active rentals and pending returns</div>
        <div class="row g-3 mb-4">
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-4 fw-bold text-primary">84</div><small class="text-secondary">Active Rentals</small></div></div>
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-4 fw-bold text-primary">12</div><small class="text-secondary">Returns Due Today</small></div></div>
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-4 fw-bold text-primary">5</div><small class="text-secondary">Pickups Today</small></div></div>
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-4 fw-bold text-danger">3</div><small class="text-secondary">Overdue</small></div></div>
        </div>
        <div class="card bg-white border">
          <div class="card-header bg-white fw-bold small">Live Rental Feed</div>
          <div class="card-body p-0">
            <table class="table table-hover small mb-0">
              <thead><tr><th>Time</th><th>Event</th><th>Member</th><th>Tool</th><th>Zone</th></tr></thead>
              <tbody>
                <tr><td>09:42 AM</td><td><span class="badge bg-success">Pickup Confirmed</span></td><td>Ahmed H.</td><td>Power Drill</td><td>Maadi</td></tr>
                <tr><td>09:15 AM</td><td><span class="badge bg-primary">Reservation Made</span></td><td>Sara M.</td><td>3D Printer</td><td>Nasr City</td></tr>
                <tr><td>08:50 AM</td><td><span class="badge bg-success">Return Confirmed</span></td><td>Omar K.</td><td>Oscilloscope</td><td>Maadi</td></tr>
                <tr><td>08:30 AM</td><td><span class="badge bg-danger">Overdue Alert</span></td><td>Hassan T.</td><td>Laser Level</td><td>Dokki</td></tr>
                <tr><td>08:00 AM</td><td><span class="badge bg-warning text-dark">Dispute Opened</span></td><td>Karim A.</td><td>Tile Saw</td><td>Maadi</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-audit">
        <div class="fw-bold fs-5 mb-1">Inventory Audit</div>
        <div class="text-secondary small mb-3">System-guided random checks to verify lenders possess their listed tools</div>
        <div class="card bg-white border">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-bold small">Audit Queue</span>
            <button class="btn btn-primary btn-sm" onclick="alert('Generating random audit assignments...')">Generate Random Audits</button>
          </div>
          <div class="card-body p-0">
            <table class="table table-hover small mb-0">
              <thead><tr><th>Lender</th><th>Tool</th><th>Last Audit</th><th>Status</th><th>Action</th></tr></thead>
              <tbody>
                <tr><td>Sara Youssef</td><td>Milwaukee Power Drill</td><td>Mar 10, 2026</td><td><span class="badge bg-warning text-dark">Due</span></td><td><button class="btn btn-primary btn-sm">Send Request</button></td></tr>
                <tr><td>Ahmed Kamel</td><td>Creality 3D Printer</td><td>Apr 1, 2026</td><td><span class="badge bg-success">Recent</span></td><td><button class="btn btn-outline-secondary btn-sm">View Report</button></td></tr>
                <tr><td>Hana Rashid</td><td>FLIR Thermal Camera</td><td>Feb 20, 2026</td><td><span class="badge bg-danger">Overdue</span></td><td><button class="btn btn-danger btn-sm">Send Urgent</button></td></tr>
                <tr><td>Yasser Kamal</td><td>Digital Oscilloscope</td><td>Apr 15, 2026</td><td><span class="badge bg-success">Recent</span></td><td><button class="btn btn-outline-secondary btn-sm">View Report</button></td></tr>
                <tr><td>Nadia Mostafa</td><td>Sewing Machine</td><td>Jan 5, 2026</td><td><span class="badge bg-danger">Overdue</span></td><td><button class="btn btn-danger btn-sm">Send Urgent</button></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-pending">
        <div class="fw-bold fs-5 mb-1">Pending Tool Approvals</div>
        <div class="text-secondary small mb-3">Review and approve new tool listings before they go live</div>
        <div class="card bg-white border">
          <div class="card-body p-0">
            <table class="table table-hover small mb-0">
              <thead><tr><th>Tool</th><th>Lender</th><th>Category</th><th>Daily Rate</th><th>Deposit</th><th>Submitted</th><th>Actions</th></tr></thead>
              <tbody>
                <tr><td>🔨 Bosch Hammer Drill</td><td>Sara M.</td><td>Power Tools</td><td>35 EGP</td><td>280 EGP</td><td>May 2</td><td><button class="btn btn-success btn-sm me-1">Approve</button><button class="btn btn-danger btn-sm">Reject</button></td></tr>
                <tr><td>📐 Laser Distance Meter</td><td>Ali K.</td><td>Measurement</td><td>20 EGP</td><td>150 EGP</td><td>May 1</td><td><button class="btn btn-success btn-sm me-1">Approve</button><button class="btn btn-danger btn-sm">Reject</button></td></tr>
                <tr><td>🧰 Socket Wrench Set</td><td>Mona H.</td><td>Hand Tools</td><td>15 EGP</td><td>100 EGP</td><td>Apr 30</td><td><button class="btn btn-success btn-sm me-1">Approve</button><button class="btn btn-danger btn-sm">Reject</button></td></tr>
                <tr><td>🖨️ Resin 3D Printer</td><td>Karim F.</td><td>3D Printing</td><td>100 EGP</td><td>800 EGP</td><td>Apr 29</td><td><button class="btn btn-success btn-sm me-1">Approve</button><button class="btn btn-danger btn-sm">Reject</button></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-qr">
        <div class="fw-bold fs-5 mb-1">QR Handover Verification</div>
        <div class="text-secondary small mb-3">Generate and scan unique QR codes to confirm physical tool transfers</div>
        <div class="row g-3">
          <div class="col-md-5">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Generate QR Code</div>
              <div class="card-body">
                <div class="mb-3"><label class="form-label text-dark">Reservation ID</label><input type="text" class="form-control" placeholder="e.g. RES-2026-0412"/></div>
                <div class="mb-3"><label class="form-label text-dark">Transfer Type</label>
                  <select class="form-select"><option>Pickup (Lender → Borrower)</option><option>Return (Borrower → Lender)</option></select>
                </div>
                <button class="btn btn-primary w-100" onclick="document.getElementById('qrbox').style.display='block'">Generate QR Code</button>
                <div id="qrbox" class="text-center mt-3 p-3 bg-light border rounded" style="display:none;">
                  <div style="font-size:4rem; color:#000;">▩▦▩<br/>▦▩▦<br/>▩▦▩</div>
                  <div class="text-dark small mt-1 fw-bold">RES-2026-0412</div>
                  <div class="text-secondary small">Valid for 30 minutes</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-7">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Recent Handovers</div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Reservation</th><th>Tool</th><th>Type</th><th>Time</th><th>Status</th></tr></thead>
                  <tbody>
                    <tr><td>RES-0410</td><td>Power Drill</td><td>Pickup</td><td>May 3, 9:00 AM</td><td><span class="badge bg-success">Confirmed</span></td></tr>
                    <tr><td>RES-0408</td><td>3D Printer</td><td>Return</td><td>May 2, 6:30 PM</td><td><span class="badge bg-success">Confirmed</span></td></tr>
                    <tr><td>RES-0405</td><td>Tile Saw</td><td>Pickup</td><td>May 1, 10:00 AM</td><td><span class="badge bg-success">Confirmed</span></td></tr>
                    <tr><td>RES-0401</td><td>Sewing Machine</td><td>Return</td><td>Apr 30, 5:00 PM</td><td><span class="badge bg-warning text-dark">Pending</span></td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-taxonomy">
        <div class="fw-bold fs-5 mb-1">Taxonomy & Category Mapping</div>
        <div class="text-secondary small mb-3">Manage the hierarchical structure of tool types for accurate search results</div>
        <div class="row g-3">
          <div class="col-md-4">
            <div class="card bg-white border">
              <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold small">Add Category</span>
              </div>
              <div class="card-body">
                <div class="mb-3"><label class="form-label text-dark">Category Name</label><input type="text" class="form-control" placeholder="e.g. Power Tools"/></div>
                <div class="mb-3"><label class="form-label text-dark">Parent Category</label>
                  <select class="form-select"><option>-- None (Top Level) --</option><option>Power Tools</option><option>Hand Tools</option><option>Measurement</option></select>
                </div>
                <div class="mb-3"><label class="form-label text-dark">Description</label><textarea class="form-control" rows="2"></textarea></div>
                <button class="btn btn-primary w-100" onclick="alert('Category added!')">Add Category</button>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Category Tree</div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Category</th><th>Parent</th><th>Tools Count</th><th>Action</th></tr></thead>
                  <tbody>
                    <tr><td>🔧 Power Tools</td><td>—</td><td>42</td><td><button class="btn btn-outline-secondary btn-sm">Edit</button></td></tr>
                    <tr><td>&nbsp;&nbsp;&nbsp;⚡ Drills</td><td>Power Tools</td><td>18</td><td><button class="btn btn-outline-secondary btn-sm">Edit</button></td></tr>
                    <tr><td>&nbsp;&nbsp;&nbsp;🪚 Saws</td><td>Power Tools</td><td>12</td><td><button class="btn btn-outline-secondary btn-sm">Edit</button></td></tr>
                    <tr><td>📐 Measurement</td><td>—</td><td>15</td><td><button class="btn btn-outline-secondary btn-sm">Edit</button></td></tr>
                    <tr><td>🖨️ 3D Printing</td><td>—</td><td>8</td><td><button class="btn btn-outline-secondary btn-sm">Edit</button></td></tr>
                    <tr><td>🧵 Sewing & Textile</td><td>—</td><td>11</td><td><button class="btn btn-outline-secondary btn-sm">Edit</button></td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-disputes">
        <div class="fw-bold fs-5 mb-1">Dispute Mediation</div>
        <div class="text-secondary small mb-3">Review evidence and decide on deposit forfeitures</div>
        <div class="card bg-white border mb-3">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-bold small text-dark">Case #1042 – Milwaukee Power Drill</span>
            <span class="badge bg-warning text-dark">Open</span>
          </div>
          <div class="card-body small">
            <div class="text-secondary mb-2">Opened May 2, 2026 · Borrower: Karim A. · Lender: Sara Y. · Deposit: 200 EGP</div>
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <div class="card bg-light border-warning p-2">
                  <div class="text-warning small fw-bold mb-1">BORROWER'S CLAIM</div>
                  <div class="small text-dark">"The drill was already damaged when I received it. The chuck was loose and the battery didn't hold charge."</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card bg-light border-primary p-2">
                  <div class="text-primary small fw-bold mb-1">LENDER'S CLAIM</div>
                  <div class="small text-dark">"The drill was in perfect condition at pickup (confirmed by QR scan). Damage was caused during use."</div>
                </div>
              </div>
            </div>
            <div class="mb-2"><label class="form-label text-dark">Librarian Decision Notes</label><textarea class="form-control form-control-sm" rows="2" placeholder="Write your decision rationale..."></textarea></div>
            <div class="d-flex gap-2 flex-wrap">
              <button class="btn btn-danger btn-sm">Release Deposit to Lender</button>
              <button class="btn btn-success btn-sm">Refund Deposit to Borrower</button>
              <button class="btn btn-warning btn-sm text-dark">Split 50/50</button>
            </div>
          </div>
        </div>
        <div class="card bg-white border">
          <div class="card-header bg-white fw-bold small">All Cases</div>
          <div class="card-body p-0">
            <table class="table table-hover small mb-0">
              <thead><tr><th>Case</th><th>Tool</th><th>Borrower</th><th>Lender</th><th>Deposit</th><th>Status</th><th>Action</th></tr></thead>
              <tbody>
                <tr><td>#1042</td><td>Power Drill</td><td>Karim A.</td><td>Sara Y.</td><td>200 EGP</td><td><span class="badge bg-warning text-dark">Open</span></td><td><button class="btn btn-outline-secondary btn-sm">Review</button></td></tr>
                <tr><td>#1039</td><td>3D Printer</td><td>Hassan T.</td><td>Ahmed K.</td><td>600 EGP</td><td><span class="badge bg-warning text-dark">Open</span></td><td><button class="btn btn-outline-secondary btn-sm">Review</button></td></tr>
                <tr><td>#1035</td><td>Tile Saw</td><td>Mona H.</td><td>Mohamed A.</td><td>500 EGP</td><td><span class="badge bg-success">Resolved</span></td><td><button class="btn btn-outline-secondary btn-sm">View</button></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-late">
        <div class="fw-bold fs-5 mb-1">Late Return Escalation</div>
        <div class="text-secondary small mb-3">Multi-stage notification system with increasing penalty tiers</div>
        <div class="card bg-white border">
          <div class="card-body p-0">
            <table class="table table-hover small mb-0">
              <thead><tr><th>Member</th><th>Tool</th><th>Due Date</th><th>Days Late</th><th>Penalty</th><th>Stage</th><th>Action</th></tr></thead>
              <tbody>
                <tr><td>Karim Amin</td><td>Milwaukee Drill</td><td>May 1</td><td><span class="badge bg-danger">2 days</span></td><td class="text-primary fw-bold">40 EGP</td><td><span class="badge bg-warning text-dark">Stage 2: SMS Sent</span></td><td><button class="btn btn-danger btn-sm">→ Stage 3</button></td></tr>
                <tr><td>Rania Mostafa</td><td>Tile Saw</td><td>May 2</td><td><span class="badge bg-warning text-dark">1 day</span></td><td class="text-primary fw-bold">55 EGP</td><td><span class="badge bg-primary">Stage 1: Email Sent</span></td><td><button class="btn btn-warning btn-sm text-dark">Send SMS</button></td></tr>
                <tr><td>Hassan Tarek</td><td>Laser Level</td><td>Apr 29</td><td><span class="badge bg-danger">4 days</span></td><td class="text-primary fw-bold">120 EGP</td><td><span class="badge bg-danger">Stage 3: Legal Notice</span></td><td><button class="btn btn-danger btn-sm">Flag Suspension</button></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-blacklist">
        <div class="fw-bold fs-5 mb-1">Blacklist Manager</div>
        <div class="text-secondary small mb-3">Temporarily or permanently restrict users based on policy violations</div>
        <div class="row g-3">
          <div class="col-md-4">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Add Restriction</div>
              <div class="card-body">
                <div class="mb-3"><label class="form-label text-dark">Member Name / ID</label><input type="text" class="form-control" placeholder="Search member..."/></div>
                <div class="mb-3"><label class="form-label text-dark">Restriction Type</label>
                  <select class="form-select"><option>Temporary Suspension (30 days)</option><option>Temporary Suspension (60 days)</option><option>Permanent Ban</option></select>
                </div>
                <div class="mb-3"><label class="form-label text-dark">Reason</label><textarea class="form-control" rows="2" placeholder="Reason for restriction..."></textarea></div>
                <button class="btn btn-danger w-100" onclick="alert('Restriction applied!')">Apply Restriction</button>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Restricted Members</div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Member</th><th>Reason</th><th>Since</th><th>Type</th><th>Action</th></tr></thead>
                  <tbody>
                    <tr><td>Hassan Tarek</td><td>4 late returns, tool damage</td><td>Apr 29</td><td><span class="badge bg-warning text-dark">Suspended 30d</span></td><td><button class="btn btn-outline-secondary btn-sm">Lift Ban</button></td></tr>
                    <tr><td>Unknown User #44</td><td>ID fraud attempt</td><td>Mar 15</td><td><span class="badge bg-danger">Permanent Ban</span></td><td><button class="btn btn-outline-secondary btn-sm">View</button></td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-insurance">
        <div class="fw-bold fs-5 mb-1">Insurance Claims</div>
        <div class="text-secondary small mb-3">Automated claim reports for high-value tool theft or total destruction</div>
        <div class="row g-3">
          <div class="col-md-4">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">New Claim</div>
              <div class="card-body">
                <div class="mb-3"><label class="form-label text-dark">Reservation ID</label><input type="text" class="form-control" placeholder="RES-XXXX"/></div>
                <div class="mb-3"><label class="form-label text-dark">Claim Type</label>
                  <select class="form-select"><option>Total Damage</option><option>Theft</option><option>Partial Damage</option></select>
                </div>
                <div class="mb-3"><label class="form-label text-dark">Estimated Value (EGP)</label><input type="number" class="form-control" placeholder="0"/></div>
                <div class="mb-3"><label class="form-label text-dark">Description</label><textarea class="form-control" rows="2"></textarea></div>
                <button class="btn btn-primary w-100" onclick="alert('Claim submitted!')">Submit Claim</button>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Open Claims</div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Claim #</th><th>Tool</th><th>Value</th><th>Type</th><th>Submitted</th><th>Status</th><th>Action</th></tr></thead>
                  <tbody>
                    <tr><td>CLM-001</td><td>FLIR Thermal Camera</td><td>4,500 EGP</td><td>Total Damage</td><td>Apr 28</td><td><span class="badge bg-warning text-dark">Under Review</span></td><td><button class="btn btn-outline-secondary btn-sm">View</button></td></tr>
                    <tr><td>CLM-002</td><td>DeWalt Router</td><td>2,200 EGP</td><td>Theft</td><td>May 1</td><td><span class="badge bg-warning text-dark">Under Review</span></td><td><button class="btn btn-outline-secondary btn-sm">View</button></td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-refund">
        <div class="fw-bold fs-5 mb-1">Refund & Credit Reconciliation</div>
        <div class="text-secondary small mb-3">Handle partial refunds if a tool breaks mid-use through no fault of the borrower</div>
        <div class="row g-3">
          <div class="col-md-4">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Process Refund</div>
              <div class="card-body">
                <div class="mb-3"><label class="form-label text-dark">Reservation ID</label><input type="text" class="form-control" placeholder="RES-XXXX"/></div>
                <div class="mb-3"><label class="form-label text-dark">Refund Type</label>
                  <select class="form-select"><option>Full Refund</option><option>Partial Refund</option><option>Platform Credit</option></select>
                </div>
                <div class="mb-3"><label class="form-label text-dark">Amount (EGP)</label><input type="number" class="form-control" placeholder="0"/></div>
                <div class="mb-3"><label class="form-label text-dark">Reason</label><textarea class="form-control" rows="2" placeholder="e.g. Tool broke mid-use due to manufacturing defect"></textarea></div>
                <button class="btn btn-primary w-100" onclick="alert('Refund processed!')">Process Refund</button>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Recent Refunds</div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Reservation</th><th>Member</th><th>Amount</th><th>Type</th><th>Reason</th><th>Status</th></tr></thead>
                  <tbody>
                    <tr><td>RES-0399</td><td>Ahmed H.</td><td class="text-primary fw-bold">120 EGP</td><td>Partial</td><td>Tool broke on day 2</td><td><span class="badge bg-success">Processed</span></td></tr>
                    <tr><td>RES-0385</td><td>Sara M.</td><td class="text-primary fw-bold">80 EGP</td><td>Platform Credit</td><td>Lender cancelled reservation</td><td><span class="badge bg-success">Processed</span></td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-assignment">
        <div class="fw-bold fs-5 mb-1">Librarian Assignment</div>
        <div class="text-secondary small mb-3">Distribute pending approvals and disputes among available staff</div>
        <div class="row g-3">
          <div class="col-md-5">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Staff Availability</div>
              <div class="card-body p-0">
                <table class="table small mb-0">
                  <thead><tr><th>Librarian</th><th>Zone</th><th>Active Tasks</th><th>Status</th></tr></thead>
                  <tbody>
                    <tr><td>Omar Farouk</td><td>Maadi</td><td>5</td><td><span class="badge bg-success">Available</span></td></tr>
                    <tr><td>Sara Kader</td><td>Nasr City</td><td>8</td><td><span class="badge bg-warning text-dark">Busy</span></td></tr>
                    <tr><td>Hani Farid</td><td>Dokki</td><td>3</td><td><span class="badge bg-success">Available</span></td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-7">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Unassigned Tasks</div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Task</th><th>Type</th><th>Priority</th><th>Assign To</th><th>Action</th></tr></thead>
                  <tbody>
                    <tr><td>Dispute #1042</td><td>Dispute</td><td><span class="badge bg-danger">High</span></td><td><select class="form-select form-select-sm"><option>Omar Farouk</option><option>Hani Farid</option></select></td><td><button class="btn btn-primary btn-sm">Assign</button></td></tr>
                    <tr><td>Audit: Hana Rashid</td><td>Audit</td><td><span class="badge bg-warning text-dark">Medium</span></td><td><select class="form-select form-select-sm"><option>Omar Farouk</option><option>Hani Farid</option></select></td><td><button class="btn btn-primary btn-sm">Assign</button></td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-revenue">
        <div class="fw-bold fs-5 mb-1">Revenue Reports</div>
        <div class="text-secondary small mb-3">Platform fees, insurance cuts, and lender earnings</div>
        <div class="row g-3 mb-4">
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-5 fw-bold text-primary">12,450 EGP</div><small class="text-secondary">Total Revenue (May)</small></div></div>
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-5 fw-bold text-primary">623 EGP</div><small class="text-secondary">Platform Fees (5%)</small></div></div>
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-5 fw-bold text-primary">9,120 EGP</div><small class="text-secondary">Lender Payouts</small></div></div>
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-5 fw-bold text-primary">2,707 EGP</div><small class="text-secondary">Deposit Balance</small></div></div>
        </div>
        <div class="card bg-white border">
          <div class="card-header bg-white fw-bold small">Monthly Breakdown</div>
          <div class="card-body p-0">
            <table class="table table-hover small mb-0">
              <thead><tr><th>Month</th><th>Rentals</th><th>Gross Revenue</th><th>Platform Fee</th><th>Lender Payouts</th><th>Insurance Cuts</th></tr></thead>
              <tbody>
                <tr><td>May 2026</td><td>84</td><td>12,450 EGP</td><td>623 EGP</td><td>9,120 EGP</td><td>310 EGP</td></tr>
                <tr><td>Apr 2026</td><td>71</td><td>10,380 EGP</td><td>519 EGP</td><td>7,650 EGP</td><td>260 EGP</td></tr>
                <tr><td>Mar 2026</td><td>65</td><td>9,100 EGP</td><td>455 EGP</td><td>6,800 EGP</td><td>225 EGP</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-promotions">
        <div class="fw-bold fs-5 mb-1">Promotional Campaigns</div>
        <div class="text-secondary small mb-3">Create time-limited discount codes for specific tool categories</div>
        <div class="row g-3">
          <div class="col-md-4">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Create Campaign</div>
              <div class="card-body">
                <div class="mb-3"><label class="form-label text-dark">Campaign Name</label><input type="text" class="form-control" placeholder="e.g. Gardening Week"/></div>
                <div class="mb-3"><label class="form-label text-dark">Tool Category</label><select class="form-select"><option>All Categories</option><option>Power Tools</option><option>Woodworking</option><option>Sewing</option></select></div>
                <div class="row g-2 mb-3">
                  <div class="col-6"><label class="form-label text-dark">Discount %</label><input type="number" class="form-control" placeholder="20"/></div>
                  <div class="col-6"><label class="form-label text-dark">Code</label><input type="text" class="form-control" placeholder="GARDEN20"/></div>
                </div>
                <div class="row g-2 mb-3">
                  <div class="col-6"><label class="form-label text-dark">Start</label><input type="date" class="form-control"/></div>
                  <div class="col-6"><label class="form-label text-dark">End</label><input type="date" class="form-control"/></div>
                </div>
                <button class="btn btn-primary w-100" onclick="alert('Campaign created!')">Launch Campaign</button>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Active Campaigns</div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Campaign</th><th>Code</th><th>Discount</th><th>Category</th><th>Expires</th><th>Status</th><th>Action</th></tr></thead>
                  <tbody>
                    <tr><td>Summer Tools</td><td>SUMMER25</td><td>25%</td><td>Power Tools</td><td>May 31</td><td><span class="badge bg-success">Active</span></td><td><button class="btn btn-outline-secondary btn-sm">Edit</button></td></tr>
                    <tr><td>New Member Welcome</td><td>WELCOME10</td><td>10%</td><td>All</td><td>Dec 31</td><td><span class="badge bg-success">Active</span></td><td><button class="btn btn-outline-secondary btn-sm">Edit</button></td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-zones">
        <div class="fw-bold fs-5 mb-1">Zone Management</div>
        <div class="text-secondary small mb-3">Define administrative boundaries for local community groups</div>
        <div class="row g-3">
          <div class="col-md-4">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Add New Zone</div>
              <div class="card-body">
                <div class="mb-3"><label class="form-label text-dark">Zone Name</label><input type="text" class="form-control" placeholder="e.g. New Cairo"/></div>
                <div class="mb-3"><label class="form-label text-dark">City</label><select class="form-select"><option>Cairo</option><option>Giza</option><option>Alexandria</option></select></div>
                <div class="mb-3"><label class="form-label text-dark">Assign Librarian</label><select class="form-select"><option>Unassigned</option><option>Omar Farouk</option><option>Sara Kader</option><option>Hani Farid</option></select></div>
                <button class="btn btn-primary w-100" onclick="alert('Zone created!')">Create Zone</button>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">All Zones</div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Zone</th><th>City</th><th>Members</th><th>Tools</th><th>Librarian</th><th>Status</th></tr></thead>
                  <tbody>
                    <tr><td>Maadi</td><td>Cairo</td><td>87</td><td>124</td><td>Omar Farouk</td><td><span class="badge bg-success">Active</span></td></tr>
                    <tr><td>Nasr City</td><td>Cairo</td><td>64</td><td>98</td><td>Sara Kader</td><td><span class="badge bg-success">Active</span></td></tr>
                    <tr><td>Heliopolis</td><td>Cairo</td><td>51</td><td>76</td><td>Unassigned</td><td><span class="badge bg-warning text-dark">Pending</span></td></tr>
                    <tr><td>Dokki</td><td>Giza</td><td>43</td><td>59</td><td>Hani Farid</td><td><span class="badge bg-success">Active</span></td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-broadcast">
        <div class="fw-bold fs-5 mb-1">Broadcast Message</div>
        <div class="text-secondary small mb-3">Notify users in specific regions about delays or important updates</div>
        <div class="card bg-white border" style="max-width:520px;">
          <div class="card-body">
            <div class="mb-3"><label class="form-label text-dark">Target Zone</label><select class="form-select"><option>All Zones</option><option>Maadi</option><option>Nasr City</option><option>Heliopolis</option><option>Dokki</option></select></div>
            <div class="mb-3"><label class="form-label text-dark">Target Audience</label><select class="form-select"><option>All Members</option><option>Borrowers Only</option><option>Lenders Only</option></select></div>
            <div class="mb-3"><label class="form-label text-dark">Channel</label><select class="form-select"><option>Email + SMS</option><option>Email Only</option><option>SMS Only</option><option>In-App Only</option></select></div>
            <div class="mb-3"><label class="form-label text-dark">Message</label><textarea class="form-control" rows="4" placeholder="e.g. Due to heavy rain today, pickup/dropoff in Maadi may be delayed by 1-2 hours..."></textarea></div>
            <button class="btn btn-primary w-100" onclick="alert('Broadcast sent!')">Send Broadcast</button>
          </div>
        </div>
      </div>

    </div></div></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function show(id, el) {
    document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
    document.getElementById('panel-' + id).classList.add('active');
    document.querySelectorAll('.sidebar .btn').forEach(b => { b.classList.remove('text-dark'); b.classList.add('text-secondary'); });
    if (el) { el.classList.remove('text-secondary'); el.classList.add('text-dark'); }
  }
  // 3shan yeb2a active 3ala awel wa7da
  document.querySelector('.sidebar .btn').classList.add('text-dark');
  document.querySelector('.sidebar .btn').classList.remove('text-secondary');
</script>
</body>
</html>