const nodemailer = require('nodemailer');
const dotenv = require('dotenv');

dotenv.config();

const {
    SMTP_HOST,
    SMTP_PORT,
    SMTP_USER,
    SMTP_PASS,
    FROM_EMAIL,
    FRONTEND_URL
} = process.env;

if (!SMTP_HOST || !SMTP_PORT || !SMTP_USER || !SMTP_PASS || !FROM_EMAIL) {
    console.warn('Mailer: Some SMTP env vars are not set. Email sending may fail.');
}

const transporter = nodemailer.createTransport({
    host: SMTP_HOST,
    port: SMTP_PORT ? parseInt(SMTP_PORT, 10) : 587,
    secure: SMTP_PORT == 465, // true for 465, false for other ports
    auth: {
        user: SMTP_USER,
        pass: SMTP_PASS
    }
});

async function sendMail(mailOptions) {
    try {
        const info = await transporter.sendMail(mailOptions);
        console.log('Mailer: Email sent:', info.messageId);
        return info;
    } catch (err) {
        console.error('Mailer: Failed to send email', err);
        throw err;
    }
}

async function sendApprovalEmail(seller) {
    const to = seller.user_email || seller.pic_email;
    const shop = seller.shop_name || '';
    const loginUrl = FRONTEND_URL ? `${FRONTEND_URL}/login` : 'https://example.com/login';

    const subject = 'Pendaftaran Penjual Disetujui — ' + shop;
    const text = `Selamat! Pendaftaran penjual Anda untuk toko "${shop}" telah disetujui. Akun penjual Anda sekarang aktif. Silakan login di: ${loginUrl}`;
    const html = `
        <p>Halo,</p>
        <p>Selamat — pendaftaran penjual Anda untuk toko <strong>${shop}</strong> telah <strong>disetujui</strong>.</p>
        <p>Akun penjual Anda sekarang <strong>Aktif</strong>. Anda dapat masuk ke dashboard penjual menggunakan tautan berikut:</p>
        <p><a href="${loginUrl}">Masuk ke platform</a></p>
        <p>Jika Anda mengalami masalah, balas email ini untuk bantuan.</p>
        <p>Salam,<br/>Tim Platform</p>
    `;

    return sendMail({
        from: FROM_EMAIL,
        to,
        subject,
        text,
        html
    });
}

async function sendRejectionEmail(seller, reason) {
    const to = seller.user_email || seller.pic_email;
    const shop = seller.shop_name || '';

    const subject = 'Pendaftaran Penjual Ditolak — ' + shop;
    const text = `Mohon maaf, pendaftaran penjual Anda untuk toko "${shop}" ditolak. Alasan: ${reason || 'Tidak diberikan'}`;
    const html = `
        <p>Halo,</p>
        <p>Mohon maaf — pendaftaran penjual Anda untuk toko <strong>${shop}</strong> <strong>ditolak</strong>.</p>
        <p><strong>Alasan:</strong> ${reason || 'Tidak diberikan'}</p>
        <p>Jika Anda ingin mencoba lagi, periksa kembali dokumen dan informasi yang diperlukan lalu ajukan pendaftaran ulang.</p>
        <p>Salam,<br/>Tim Platform</p>
    `;

    return sendMail({
        from: FROM_EMAIL,
        to,
        subject,
        text,
        html
    });
}

async function sendThankyouEmail(user, product) {
    const to = user.email;
    const productName = product.name || '';

    const subject = 'Terima Kasih Telah Membuat Review Produk ' + productName + ' <3';

    const text = `
    Hi ${user.name || ''},

    Terima kasih telah meluangkan waktu untuk menulis ulasan mengenai produk "${productName}".

    Masukan Anda sangat berarti bagi kami untuk meningkatkan kualitas layanan dan membantu pengguna lain membuat keputusan yang lebih baik.

    Hormat kami,
    Tim Support
    `;

        const html = `
    <div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #4A90E2;">Terima Kasih atas Review Anda!</h2>

    <p>Hi <strong>${user.name || ''}</strong>,</p>

    <p>
        Terima kasih telah meluangkan waktu untuk memberikan ulasan mengenai produk 
        <strong>"${productName}"</strong>.
    </p>

    <p>
        Masukan Anda sangat berharga bagi kami untuk meningkatkan kualitas produk dan
        membantu pengguna lain membuat keputusan yang lebih baik.
    </p>

    <p>Hormat kami,<br><strong>Tim Support</strong></p>

    <hr style="margin-top: 30px;" />
    <small>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</small>
    </div>
    `;

    return sendMail({
        from: FROM_EMAIL,
        to,
        subject,
        text,
        html
    });
}


module.exports = {
    sendApprovalEmail,
    sendRejectionEmail,
    sendThankyouEmail,
    transporter
};
