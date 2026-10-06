# Contact Form 7 setup for Tailor-Made and contact emails

## 1. Create the tailor-made form

Create a new form in Contact Form 7 and keep its form ID exactly as CF7 gives it to you. The plugin now renders the tailor-made overlay globally in the footer with the default ID 6f7a331, so a button with the class below is enough to open it.

If you want to render the modal explicitly or override the default form ID on a specific page, use this shortcode anywhere in the site:

[tailor_made_form cf7_id="123"]

If you want the shortcode to render the trigger button too:

[tailor_made_form cf7_id="123" button_text="Plan your tailor-made journey"]

If you already have a custom button in Elementor, give that button the class:

dg-tailor-made-trigger

The existing Tour Finder result button already opens this popup automatically.

With your current setup, the remaining requirement is simply this:

- Keep the button class as dg-tailor-made-trigger
- Keep the CF7 form published with ID 6f7a331
- Make sure Contact Form 7 is active on the site

## 2. Suggested form markup

Paste this into the Form tab in Contact Form 7:

```text
<div class="we-form-grid">
  <div class="we-form-field">
    <label>Full name *</label>
    [text* your-name autocomplete:name]
  </div>
  <div class="we-form-field">
    <label>Email address *</label>
    [email* your-email autocomplete:email]
  </div>
  <div class="we-form-field">
    <label>Phone</label>
    [tel your-phone autocomplete:tel]
  </div>
  <div class="we-form-field">
    <label>Preferred travel month</label>
    [select travel-month include_blank "January" "February" "March" "April" "May" "June" "July" "August" "September" "October" "November" "December"]
  </div>
  <div class="we-form-field">
    <label>Estimated length</label>
    [select travel-length include_blank "3-5 days" "6-8 days" "9-12 days" "13+ days"]
  </div>
  <div class="we-form-field">
    <label>Travelers</label>
    [number travelers min:1 max:20]
  </div>
</div>

<div class="we-form-field we-form-full">
  <label>Main interests</label>
  [checkbox interests use_label_element "Birding" "Wildlife" "Nature" "Hiking" "Culture" "Photography"]
</div>

<div class="we-form-field we-form-full">
  <label>Budget per traveler</label>
  [select budget include_blank "Under $2,500" "$2,500-$4,000" "$4,000-$6,500" "$6,500+"]
</div>

<div class="we-form-field we-form-full">
  <label>Tell us what you want from this journey</label>
  [textarea* your-message 6x40]
</div>

[hidden inquiry-type]
[hidden page-url]
[hidden page-title]

[submit "Send tailor-made request"]
```

## 3. Mail tab settings

Use a real mailbox from your domain in the From field. Example:

- To: your real destination mailbox
- From: Wildendemics <noreply@yourdomain.com>
- Subject: Tailor-made request | [your-name]
- Additional headers: Reply-To: [your-email]
- Message body: switch to HTML and paste the template below

## 4. HTML email body for contact or tailor-made requests

Use this body in Mail. You can reuse it for the general contact form too. Replace fields if your form uses different names.

This template is the visual base shared with booking emails. Keep the same layout and only change the heading and field rows according to the form type.

```html
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Contact Message</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f6f8; padding:40px 20px;">
  <tr>
    <td align="center">
      <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">
        <tr>
          <td style="background:#1a3a2f; padding:35px 30px; text-align:center;">
            <img src="https://nilsbirding.com/wp-content/uploads/2026/05/LOGO-GRANDE-1.png" alt="Nils Birding & Conservation" style="max-width:220px; height:auto; display:block; margin:0 auto 15px auto;">
            <p style="margin:0; color:rgba(255,255,255,0.9); font-size:15px; font-weight:400; letter-spacing:0.5px;">Nueva solicitud de planificación a medida</p>
          </td>
        </tr>
        <tr>
          <td style="padding:0 30px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:-16px;">
              <tr>
                <td align="center">
                  <span style="display:inline-block; background:#f59e0b; color:#ffffff; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; padding:8px 24px; border-radius:20px; box-shadow:0 2px 8px rgba(245,158,11,0.3);">⏳ Pendiente de coordinación</span>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td style="padding:30px 30px 20px 30px;">
            <h3 style="margin:0 0 16px 0; color:#1e293b; font-size:16px; font-weight:700;">👤 Datos del cliente</h3>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr><td style="padding:12px 0; border-bottom:1px solid #f1f5f9;"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40" style="vertical-align:top;"><div style="width:36px; height:36px; background:#eef4f8; border-radius:10px; text-align:center; line-height:36px; font-size:16px;">👤</div></td><td style="padding-left:12px; vertical-align:top;"><p style="margin:0 0 2px 0; color:#94a3b8; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Nombre completo</p><p style="margin:0; color:#1e293b; font-size:15px; font-weight:600;">[your-name]</p></td></tr></table></td></tr>
              <tr><td style="padding:12px 0; border-bottom:1px solid #f1f5f9;"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40" style="vertical-align:top;"><div style="width:36px; height:36px; background:#fef2f2; border-radius:10px; text-align:center; line-height:36px; font-size:16px;">✉️</div></td><td style="padding-left:12px; vertical-align:top;"><p style="margin:0 0 2px 0; color:#94a3b8; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Correo electrónico</p><p style="margin:0; color:#1e293b; font-size:15px; font-weight:600;">[your-email]</p></td></tr></table></td></tr>
              <tr><td style="padding:12px 0; border-bottom:1px solid #f1f5f9;"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40" style="vertical-align:top;"><div style="width:36px; height:36px; background:#fffbeb; border-radius:10px; text-align:center; line-height:36px; font-size:16px;">📅</div></td><td style="padding-left:12px; vertical-align:top;"><p style="margin:0 0 2px 0; color:#94a3b8; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Mes de viaje deseado</p><p style="margin:0; color:#1e293b; font-size:15px; font-weight:600;">[travel-month]</p></td></tr></table></td></tr>
              <tr><td style="padding:12px 0; border-bottom:1px solid #f1f5f9;"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40" style="vertical-align:top;"><div style="width:36px; height:36px; background:#fffbeb; border-radius:10px; text-align:center; line-height:36px; font-size:16px;">⏱</div></td><td style="padding-left:12px; vertical-align:top;"><p style="margin:0 0 2px 0; color:#94a3b8; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Duración estimada</p><p style="margin:0; color:#1e293b; font-size:15px; font-weight:600;">[travel-length]</p></td></tr></table></td></tr>
              <tr><td style="padding:12px 0; border-bottom:1px solid #f1f5f9;"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40" style="vertical-align:top;"><div style="width:36px; height:36px; background:#eef4f8; border-radius:10px; text-align:center; line-height:36px; font-size:16px;">👥</div></td><td style="padding-left:12px; vertical-align:top;"><p style="margin:0 0 2px 0; color:#94a3b8; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Viajeros</p><p style="margin:0; color:#1e293b; font-size:15px; font-weight:600;">[travelers]</p></td></tr></table></td></tr>
            </table>
          </td>
        </tr>
        <tr>
          <td style="padding:0 30px 20px 30px;">
            <h3 style="margin:0 0 16px 0; color:#1e293b; font-size:16px; font-weight:700;">📌 Preferencias del viaje</h3>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr><td style="padding:12px 0; border-bottom:1px solid #f1f5f9;"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40" style="vertical-align:top;"><div style="width:36px; height:36px; background:#eef4f8; border-radius:10px; text-align:center; line-height:36px; font-size:16px;">🧭</div></td><td style="padding-left:12px; vertical-align:top;"><p style="margin:0 0 2px 0; color:#94a3b8; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Intereses principales</p><p style="margin:0; color:#1e293b; font-size:15px; font-weight:600;">[interests]</p></td></tr></table></td></tr>
              <tr><td style="padding:12px 0; border-bottom:1px solid #f1f5f9;"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40" style="vertical-align:top;"><div style="width:36px; height:36px; background:#fef2f2; border-radius:10px; text-align:center; line-height:36px; font-size:16px;">💰</div></td><td style="padding-left:12px; vertical-align:top;"><p style="margin:0 0 2px 0; color:#94a3b8; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Presupuesto por viajero</p><p style="margin:0; color:#1e293b; font-size:15px; font-weight:600;">[budget]</p></td></tr></table></td></tr>
              <tr><td style="padding:12px 0;"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40" style="vertical-align:top;"><div style="width:36px; height:36px; background:#fffbeb; border-radius:10px; text-align:center; line-height:36px; font-size:16px;">✍️</div></td><td style="padding-left:12px; vertical-align:top;"><p style="margin:0 0 2px 0; color:#94a3b8; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Mensaje</p><p style="margin:0; color:#1e293b; font-size:15px; line-height:1.7;">[your-message]</p></td></tr></table></td></tr>
            </table>
          </td>
        </tr>
        <tr>
          <td style="padding:0 30px;"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="border-top:2px dashed #e2e8f0;"></td></tr></table></td>
        </tr>
        <tr>
          <td style="padding:20px 30px 30px 30px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f8fafc; border-radius:10px;">
              <tr>
                <td style="padding:16px 20px;">
                  <p style="margin:0 0 8px 0; color:#64748b; font-size:13px; font-weight:600;">📋 Acción requerida</p>
                  <p style="margin:0; color:#475569; font-size:13px; line-height:1.6;">Este cliente ha enviado una solicitud a medida. Por favor, responder directamente por correo o teléfono para coordinar fechas, presupuesto y preferencias del viaje.</p>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td style="background:#2c3e50; padding:24px 30px; text-align:center;">
            <p style="margin:0 0 6px 0; color:#94a3b8; font-size:13px;">Nils Birding & Conservation</p>
            <p style="margin:0; color:#64748b; font-size:12px;">Cuba · Birding Tours & Conservation</p>
          </td>
        </tr>
      </table>
      <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; margin-top:16px;">
        <tr>
          <td style="text-align:center;"><p style="margin:0; color:#94a3b8; font-size:11px;">This is an automated email generated by the contact system.</p></td>
        </tr>
      </table>
    </td>
  </tr>
</table>

</body>
</html>
```

## 5. Recommended extra mail-tags for tailor-made

Add these lines below the main message if you want more trip-planning context in the email:

```html
<p><strong>Preferred month:</strong> [travel-month]</p>
<p><strong>Length:</strong> [travel-length]</p>
<p><strong>Travelers:</strong> [travelers]</p>
<p><strong>Interests:</strong> [interests]</p>
<p><strong>Budget:</strong> [budget]</p>
<p><strong>Phone:</strong> [your-phone]</p>
<p><strong>Inquiry type:</strong> [inquiry-type]</p>
<p><strong>Page title:</strong> [page-title]</p>
<p><strong>Page URL:</strong> [page-url]</p>
```

## 6. Mail delivery recommendation

If the form sends but the email does not arrive, install an SMTP plugin and configure it with the mailbox you use in the From field. CF7 alone does not guarantee delivery on many hosting setups.
