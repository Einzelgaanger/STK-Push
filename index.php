<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BizLens - Subscription Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: var(--primary-color);
        }
        
        .payment-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo h1 {
            color: var(--primary-color);
            font-weight: 700;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 2px solid #e9ecef;
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: none;
        }
        
        .btn-pay {
            background-color: var(--secondary-color);
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            margin-top: 20px;
        }
        
        .btn-pay:hover {
            background-color: #2980b9;
        }
        
        .payment-info {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            font-size: 0.9em;
        }
        
        .alert {
            display: none;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-container">
            <div class="logo">
                <h1>BizLens</h1>
                <p class="text-muted">Subscription Payment</p>
            </div>
            
            <div class="alert alert-success" role="alert" id="successAlert">
                Payment initiated successfully! Please check your phone to complete the payment.
            </div>
            
            <div class="alert alert-danger" role="alert" id="errorAlert">
                An error occurred. Please try again.
            </div>
            
            <form id="paymentForm" method="POST" action="stk_push.php">
                <div class="mb-3">
                    <label for="phonenumber" class="form-label">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" class="form-control" id="phonenumber" name="phonenumber" 
                               placeholder="254700861129" pattern="^254[0-9]{9}$" required>
                    </div>
                    <div class="form-text">Enter your M-Pesa number starting with 254</div>
                </div>
                
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount (KES)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
                        <input type="number" class="form-control" id="amount" name="amount" 
                               placeholder="100" min="1" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-pay" name="submit">
                    <i class="fas fa-lock me-2"></i>Pay Now
                </button>
            </form>
            
            <div class="payment-info">
                <h6><i class="fas fa-info-circle me-2"></i>Payment Information</h6>
                <p class="mb-1">Paybill: 880100</p>
                <p class="mb-1">Account: 9511840014</p>
                <p class="mb-0">Direct M-Pesa: 0700861129</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Hide any previous alerts
            document.getElementById('successAlert').style.display = 'none';
            document.getElementById('errorAlert').style.display = 'none';
            
            // Get form data
            const formData = new FormData(this);
            
            // Send the request
            fetch('stk_push.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    document.getElementById('successAlert').style.display = 'block';
                    document.getElementById('errorAlert').style.display = 'none';
                } else {
                    document.getElementById('errorAlert').style.display = 'block';
                    document.getElementById('successAlert').style.display = 'none';
                }
            })
            .catch(error => {
                document.getElementById('errorAlert').style.display = 'block';
                document.getElementById('successAlert').style.display = 'none';
            });
        });
    </script>
</body>
</html>