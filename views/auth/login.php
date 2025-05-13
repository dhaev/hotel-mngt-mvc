<div id="id01" class="w3-modal">
    <div class="w3-modal-content w3-animate-top w3-card-2">
        <header class="w3-container w3-brown">
            <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-display-topright w3-xlarge">&times;</span>
            <h3 class="w3-center">Login</h3>
        </header>
        <div class="w3-container">
            <form id="loginForm" action="<?= BASE_URL ?>index.php?controller=Auth&action=login" method="post">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="form-row justify-content-center" id="error-message" class="text-danger"></div>
                <div class="form-row justify-content-center" id="success-message" class="text-success"></div>
                <div class="form-row justify-content-center mt-5">
                    <div class="form-group col-md-4">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" autocomplete="username" required>
                    </div>
                </div>
                <div class="form-row justify-content-center mt-2">
                    <div class="form-group col-md-4">
                        <label for="pwd">Password</label>
                        <input type="password" class="form-control" id="pwd" name="pwd" autocomplete="password" required>
                    </div>
                </div>
                <div class="form-row justify-content-center">
                    <button class="btn btn-secondary form-group col-md-4 text-center" type="submit" id="log_in" name="login">Submit</button>
                </div>
            </form>
            <script>
                document.getElementById('loginForm').addEventListener('submit', function(event) {
                    event.preventDefault();
                    const formData = new FormData(this);

                    // Validate CSRF token before sending the request
                    if (!formData.get('csrf_token')) {
                        document.getElementById('error-message').textContent = 'Invalid CSRF token.';
                        return;
                    }

                    fetch(this.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            document.getElementById('success-message').textContent = data.message;
                            document.getElementById('error-message').textContent = '';
                            setTimeout(() => {
                                window.location.href = data.url;
                            }, 1000);
                        } else {
                            document.getElementById('error-message').textContent = data.message;
                            document.getElementById('success-message').textContent = '';
                        }
                    })
                    .catch(error => {
                        document.getElementById('error-message').textContent = 'An error occurred: ' + error.message;
                        document.getElementById('success-message').textContent = '';
                    });
                });
            </script>
        </div>
    </div>
</div>
