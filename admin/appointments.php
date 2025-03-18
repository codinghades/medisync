<?php
include '../includes/adminSidebar.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "schema";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['update_status'])) {
    $appointment_id = $_POST['appointment_id'];
    $new_status = $_POST['new_status'];
    
    $update_sql = "UPDATE appointments SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $new_status, $appointment_id);
    
    if ($stmt->execute()) {
        $status_message = "Appointment status updated successfully";
    } else {
        $status_message = "Error updating status: " . $stmt->error;
    }
    $stmt->close();
}

if (isset($_GET['delete'])) {
    $appointment_id = $_GET['delete'];
    
    $delete_sql = "DELETE FROM appointments WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $appointment_id);
    
    if ($stmt->execute()) {
        $delete_message = "Appointment deleted successfully";
    } else {
        $delete_message = "Error deleting appointment: " . $stmt->error;
    }
    $stmt->close();
}


$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';
$appointment_type_filter = isset($_GET['appointment_type']) ? $_GET['appointment_type'] : '';


$sql = "SELECT * FROM appointments WHERE 1=1";

if ($status_filter != 'all') {
    $sql .= " AND status = '$status_filter'";
}

if (!empty($date_filter)) {
    $sql .= " AND date = '$date_filter'";
}

if (!empty($appointment_type_filter)) {
    $sql .= " AND appointment_type = '$appointment_type_filter'";
}

$sql .= " ORDER BY date, time";

$result = $conn->query($sql);

$types_sql = "SELECT DISTINCT appointment_type FROM appointments ORDER BY appointment_type";
$types_result = $conn->query($types_sql);
$appointment_types = [];
if ($types_result->num_rows > 0) {
    while($type_row = $types_result->fetch_assoc()) {
        $appointment_types[] = $type_row['appointment_type'];
    }
}

$calendar_sql = "SELECT date, COUNT(*) as count FROM appointments GROUP BY date";
$calendar_result = $conn->query($calendar_sql);
$appointments_by_date = [];

if ($calendar_result->num_rows > 0) {
    while($date_row = $calendar_result->fetch_assoc()) {
        $appointments_by_date[$date_row['date']] = $date_row['count'];
    }
}

$today = date('Y-m-d');
$today_sql = "SELECT * FROM appointments WHERE date = '$today' ORDER BY time";
$today_result = $conn->query($today_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Appointments</title>
    <link rel="stylesheet" href="../assets/css/appointments.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="mainContainer">
        <div class="Container">
            <div class="middlebar">
                <h1>Appointments</h1>
                
                <?php if(isset($status_message)): ?>
                <div class="alert alert-success"><?php echo $status_message; ?></div>
                <?php endif; ?>
                
                <?php if(isset($delete_message)): ?>
                <div class="alert alert-success"><?php echo $delete_message; ?></div>
                <?php endif; ?>
                
                <div class="filter-container">
                    <form method="GET" action="" class="filter-form">
                        <div class="filter-group">
                            <label>Status:</label>
                            <select name="status">
                                <option value="all" <?php echo $status_filter == 'all' ? 'selected' : ''; ?>>All</option>
                                <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="confirmed" <?php echo $status_filter == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="completed" <?php echo $status_filter == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="cancelled" <?php echo $status_filter == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label>Date:</label>
                            <input type="date" name="date" value="<?php echo $date_filter; ?>">
                        </div>
                        
                        <div class="filter-group">
                            <label>Type:</label>
                            <select name="appointment_type">
                                <option value="">All Types</option>
                                <?php foreach($appointment_types as $type): ?>
                                <option value="<?php echo $type; ?>" <?php echo $appointment_type_filter == $type ? 'selected' : ''; ?>><?php echo $type; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <button type="submit" class="filter-btn">
                            <i class='bx bx-filter'></i> Filter
                        </button>
                        
                        <a href="appointments.php" class="reset-btn">Reset</a>
                    </form>
                </div>
                
                <div class="appointments-table-container">
                    <table class="appointments-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Patient Name</th>
                                <th>Contact</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Type</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                
                                    $status = isset($row['status']) ? $row['status'] : 'pending';
                            ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['contact']; ?></td>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo $row['time']; ?></td>
                                <td><?php echo $row['appointment_type']; ?></td>
                                <td class="reason-cell">
                                    <div class="reason-text"><?php echo substr($row['reason'], 0, 50); ?><?php echo (strlen($row['reason']) > 50) ? '...' : ''; ?></div>
                                    <?php if(strlen($row['reason']) > 50): ?>
                                    <div class="reason-tooltip"><?php echo $row['reason']; ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $status; ?>">
                                        <?php echo ucfirst($status); ?>
                                    </span>
                                </td>
                                <td class="actions-cell">
                                    <div class="dropdown">
                                        <button class="action-btn"><i class='bx bx-dots-vertical-rounded'></i></button>
                                        <div class="dropdown-content">
                                            <form method="POST" action="">
                                                <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                                <input type="hidden" name="update_status" value="1">
                                                <button type="submit" name="new_status" value="pending" class="dropdown-item">Set Pending</button>
                                                <button type="submit" name="new_status" value="confirmed" class="dropdown-item">Set Confirmed</button>
                                                <button type="submit" name="new_status" value="completed" class="dropdown-item">Set Completed</button>
                                                <button type="submit" name="new_status" value="cancelled" class="dropdown-item">Set Cancelled</button>
                                            </form>
                                            <a href="appointments.php?delete=<?php echo $row['id']; ?>" class="dropdown-item delete-btn" onclick="return confirm('Are you sure you want to delete this appointment?')">Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                }
                            } else {
                            ?>
                            <tr>
                                <td colspan="9" class="no-records">No appointments found</td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="rightbar">
                <h2>Calendar</h2>
                <div class="box1" id="calendar">
                    <div class="calendar-header">
                        <button id="prevMonth"><i class='bx bx-chevron-left'></i></button>
                        <h3 id="currentMonth"></h3>
                        <button id="nextMonth"><i class='bx bx-chevron-right'></i></button>
                    </div>
                    <div class="calendar-days">
                        <div class="weekday">Sun</div>
                        <div class="weekday">Mon</div>
                        <div class="weekday">Tue</div>
                        <div class="weekday">Wed</div>
                        <div class="weekday">Thu</div>
                        <div class="weekday">Fri</div>
                        <div class="weekday">Sat</div>
                    </div>
                    <div class="calendar-dates" id="calendarDates"></div>
                </div>
                
                <h2>Today's Appointments</h2>
                <div class="today-appointments">
                    <?php 
                    if ($today_result->num_rows > 0) {
                        while($today_row = $today_result->fetch_assoc()) {
                         
                            $today_status = isset($today_row['status']) ? $today_row['status'] : 'pending';
                    ?>
                    <div class="appointment-card">
                        <div class="appointment-time"><?php echo date('h:i A', strtotime($today_row['time'])); ?></div>
                        <div class="appointment-details">
                            <h4><?php echo $today_row['name']; ?></h4>
                            <p><?php echo $today_row['appointment_type']; ?></p>
                        </div>
                        <div class="appointment-status <?php echo $today_status; ?>">
                            <?php echo ucfirst($today_status); ?>
                        </div>
                    </div>
                    <?php 
                        }
                    } else {
                    ?>
                    <div class="no-appointments">No appointments scheduled for today</div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        
        const appointmentDates = <?php echo json_encode($appointments_by_date); ?>;
        
      
        document.addEventListener('DOMContentLoaded', function() {
            
            const today = new Date();
            let currentMonth = today.getMonth();
            let currentYear = today.getFullYear();
            
           
            document.getElementById('prevMonth').addEventListener('click', function() {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                renderCalendar(currentMonth, currentYear);
            });
            
            document.getElementById('nextMonth').addEventListener('click', function() {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                renderCalendar(currentMonth, currentYear);
            });
            
           
            renderCalendar(currentMonth, currentYear);
            
            
            const dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                    this.classList.toggle('active');
                });
            });
            
            
            document.addEventListener('click', function() {
                dropdowns.forEach(dropdown => {
                    dropdown.classList.remove('active');
                });
            });
        });
        
        function renderCalendar(month, year) {
            const calendarDates = document.getElementById('calendarDates');
            const monthHeader = document.getElementById('currentMonth');
            
           
            calendarDates.innerHTML = '';
            
          
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                                'July', 'August', 'September', 'October', 'November', 'December'];
            monthHeader.textContent = `${monthNames[month]} ${year}`;
            
            
            const firstDay = new Date(year, month, 1).getDay();
            
   
            const daysInMonth = new Date(year, month + 1, 0).getDate();
          
            const today = new Date();
            const todayDate = today.getDate();
            const todayMonth = today.getMonth();
            const todayYear = today.getFullYear();
            
           
            const prevMonthDays = new Date(year, month, 0).getDate();
            for (let i = firstDay - 1; i >= 0; i--) {
                const dayCell = document.createElement('div');
                dayCell.classList.add('date-cell', 'other-month');
                dayCell.textContent = prevMonthDays - i;
                calendarDates.appendChild(dayCell);
            }
            
            
            for (let i = 1; i <= daysInMonth; i++) {
                const dayCell = document.createElement('div');
                dayCell.classList.add('date-cell');
                dayCell.textContent = i;
                
               
                if (i === todayDate && month === todayMonth && year === todayYear) {
                    dayCell.classList.add('today');
                }
                
               
                const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                if (appointmentDates[formattedDate]) {
                    dayCell.classList.add('has-appointments');
                    dayCell.setAttribute('data-count', appointmentDates[formattedDate]);
                }
                
               
                dayCell.addEventListener('click', function() {
                    window.location.href = `appointments.php?date=${formattedDate}`;
                });
                
                calendarDates.appendChild(dayCell);
            }
            
           
            const totalCells = firstDay + daysInMonth;
            const remainingCells = 7 - (totalCells % 7);
            if (remainingCells < 7) {
                for (let i = 1; i <= remainingCells; i++) {
                    const dayCell = document.createElement('div');
                    dayCell.classList.add('date-cell', 'other-month');
                    dayCell.textContent = i;
                    calendarDates.appendChild(dayCell);
                }
            }
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>