<?php
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$formStatus  = null; // 'success' | 'error'
$formMessage = '';
$old = ['name' => '', 'email' => '', 'phone' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $old['name']    = trim($_POST['name'] ?? '');
    $old['email']   = trim($_POST['email'] ?? '');
    $old['phone']   = trim($_POST['phone'] ?? '');
    $old['message'] = trim($_POST['message'] ?? '');
    $honeypot       = trim($_POST['website'] ?? '');

    if ($honeypot !== '') {
        // Spam bots fill hidden fields; pretend success and send nothing.
        $formStatus  = 'success';
        $formMessage = 'Thank you! Your message has been sent. We will get back to you soon.';
        $old = ['name' => '', 'email' => '', 'phone' => '', 'message' => ''];
    } elseif ($old['name'] === '' || $old['email'] === '' || $old['phone'] === '' || $old['message'] === '') {
        $formStatus  = 'error';
        $formMessage = 'Please fill in all fields before submitting.';
    } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $formStatus  = 'error';
        $formMessage = 'Please enter a valid email address.';
    } else {
        try {
            require __DIR__ . '/includes/db.php';
            $insert = $pdo->prepare(
                'INSERT INTO contact_submissions (name, email, phone, message, ip_address, created_at) VALUES (:name, :email, :phone, :message, :ip, NOW())'
            );
            $insert->execute([
                'name'    => $old['name'],
                'email'   => $old['email'],
                'phone'   => $old['phone'],
                'message' => $old['message'],
                'ip'      => $_SERVER['REMOTE_ADDR'] ?? null,
            ]);
        } catch (Throwable $e) {
            error_log('Failed to store contact submission: ' . $e->getMessage());
        }

        $config = file_exists(__DIR__ . '/mail-config.php') ? require __DIR__ . '/mail-config.php' : null;

        if (!$config || empty($config['smtp_username']) || $config['smtp_username'] === 'your-gmail-address@gmail.com') {
            $formStatus  = 'error';
            $formMessage = 'Mail is not configured yet on the server. Please call us instead.';
        } else {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = $config['smtp_username'];
                $mail->Password   = $config['smtp_password'];
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom($config['smtp_username'], 'Arpann Township Website');
                $mail->addAddress($config['mail_to'], $config['mail_to_name'] ?? '');
                $mail->addReplyTo($old['email'], $old['name']);

                $mail->isHTML(true);
                $mail->Subject = 'New enquiry from website - ' . $old['name'];
                $mail->Body    = '<h3>New Contact Form Submission</h3>'
                    . '<p><strong>Name:</strong> ' . htmlspecialchars($old['name']) . '</p>'
                    . '<p><strong>Email:</strong> ' . htmlspecialchars($old['email']) . '</p>'
                    . '<p><strong>Phone:</strong> ' . htmlspecialchars($old['phone']) . '</p>'
                    . '<p><strong>Message:</strong><br>' . nl2br(htmlspecialchars($old['message'])) . '</p>';
                $mail->AltBody = "Name: {$old['name']}\nEmail: {$old['email']}\nPhone: {$old['phone']}\nMessage:\n{$old['message']}";

                $mail->send();

                $formStatus  = 'success';
                $formMessage = 'Thank you! Your message has been sent. We will get back to you soon.';
                $old = ['name' => '', 'email' => '', 'phone' => '', 'message' => ''];
            } catch (Exception $e) {
                $formStatus  = 'error';
                $formMessage = 'Sorry, your message could not be sent right now. Please call us instead.';
            }
        }
    }
}

$pageTitle = 'Contact Us - ARPAN TOWNSHIP Saharanpur';
include __DIR__ . '/header.php';
?>

        <section id="section-hero" class="section-dark text-light no-top no-bottom relative overflow-hidden mh-600 jarallax">
            <img src="images/background/1.webp" class="jarallax-img" alt="">
            <div class="gradient-edge-top op-6"></div>
            <div class="abs w-80 bottom-10 z-2 w-100">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="relative overflow-hidden">
                                <div class="wow fadeInUpBig" data-wow-duration="1.5s">
                                    <h1 class="fs-120 text-uppercase fs-sm-10vw mb-2 lh-1">Contact Us</h1>
                                    <h3>We'd Love to Hear From You</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sw-overlay op-5"></div>
        </section>

        <section id="section-contact">
            <div class="container">
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-4">
                        <div class="d-flex mb-4">
                            <i class="fs-32 id-color icon_pin"></i>
                            <div class="ms-3">
                                <h4 class="mb-0">Site Office</h4>
                                <p>Arpann Township, Mini Bypass,<br>Near Ambala Road, Saharanpur-247001</p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <i class="fs-32 id-color icon_phone"></i>
                            <div class="ms-3">
                                <h4 class="mb-0">Call Us</h4>
                                <p>+91 8607 636363 | +91 8607 112233</p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <i class="fs-32 id-color icon_mail"></i>
                            <div class="ms-3">
                                <h4 class="mb-0">Email Us</h4>
                                <p>arpanntownship@gmail.com</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <form name="contactForm" id="contact_form" method="post" action="contact.php">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Your Name" value="<?php echo htmlspecialchars($old['name']); ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Your Email" value="<?php echo htmlspecialchars($old['email']); ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <input type="tel" name="phone" id="phone" class="form-control" placeholder="Your Phone Number" value="<?php echo htmlspecialchars($old['phone']); ?>" required>
                                </div>

                                <div class="col-md-6" style="position:absolute; left:-9999px;" aria-hidden="true">
                                    <label for="website">Website</label>
                                    <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                                </div>

                                <div class="col-md-12">
                                    <textarea name="message" id="message" class="form-control h-150px" placeholder="Your Message" required><?php echo htmlspecialchars($old['message']); ?></textarea>
                                </div>

                                <div class="col-md-12">
                                    <div class="text-center">
                                        <button type="submit" name="contact_submit" value="1" id="send_message" class="btn-main">Send Message</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

<?php if ($formStatus): ?>
    <style>
        .form-popup-overlay {
            position: fixed;
            inset: 0;
            background: rgba(16, 60, 59, 0.55);
            z-index: 20000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .form-popup-box {
            display: block !important;
            background: #fff;
            max-width: 420px;
            width: 100%;
            border-radius: 10px;
            padding: 36px 28px 28px;
            text-align: center;
            position: relative;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .form-popup-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .form-popup-box.is-success .form-popup-icon {
            background: #103c3b;
            color: #fff;
        }

        .form-popup-box.is-error .form-popup-icon {
            background: #b3261e;
            color: #fff;
        }

        .form-popup-message {
            font-size: 16px;
            color: #222;
            margin: 0 0 22px;
        }

        .form-popup-close {
            background: #103c3b;
            color: #fff;
            border: none;
            border-radius: 30px;
            padding: 10px 28px;
            font-weight: 600;
            letter-spacing: .3px;
            cursor: pointer;
        }

        .form-popup-box.is-error .form-popup-close {
            background: #b3261e;
        }

        .form-popup-dismiss {
            position: absolute;
            top: 10px;
            right: 14px;
            background: none;
            border: none;
            font-size: 22px;
            line-height: 1;
            color: #999;
            cursor: pointer;
        }
    </style>
    <div class="form-popup-overlay" id="formPopup">
        <div class="form-popup-box <?php echo $formStatus === 'success' ? 'is-success' : 'is-error'; ?>">
            <button type="button" class="form-popup-dismiss" onclick="document.getElementById('formPopup').remove()">&times;</button>
            <div class="form-popup-icon">
                <?php if ($formStatus === 'success'): ?>
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <?php else: ?>
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <?php endif; ?>
            </div>
            <p class="form-popup-message"><?php echo htmlspecialchars($formMessage); ?></p>
            <button type="button" class="form-popup-close" onclick="document.getElementById('formPopup').remove()">OK</button>
        </div>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/footer.php'; ?>
