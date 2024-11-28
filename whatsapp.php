<?php
/*
Plugin Name: Whatsapp Chat Simple
Plugin URI: 
Description: Plugin untuk menambahkan tombol WhatsApp chat di website WordPress dengan counter klik.
Version: 1.1
Author: Ramadi
Author URI: 
*/

// Mengamankan file
if (!defined('ABSPATH')) {
    exit;
}

// Fungsi untuk menambahkan WhatsApp chat ke footer
function ggi_whatsapp_chat_button() {
    $phone_number = get_option('ggi_whatsapp_phone', '6281277222991'); // Ganti dengan nomor WhatsApp Anda
    $message = get_option('ggi_whatsapp_message', 'Halo, saya ingin bertanya tentang GGi Hotel Batam.'); // Pesan default

    echo '<div id="ggi-whatsapp-chat" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
        <a href="https://wa.me/' . $phone_number . '?text=' . urlencode($message) . '" target="_blank" style="text-decoration: none;" onclick="ggiRecordClick()">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="Chat WhatsApp" style="width: 60px; height: 60px; border-radius: 50%; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        </a>
    </div>
    <script>
        function ggiRecordClick() {
            fetch("' . admin_url('admin-ajax.php') . '?action=ggi_whatsapp_click");
        }
    </script>';
}
add_action('wp_footer', 'ggi_whatsapp_chat_button');

// Fungsi untuk merekam jumlah klik
function ggi_whatsapp_click_counter() {
    // Ambil jumlah klik saat ini
    $current_count = get_option('ggi_whatsapp_click_count', 0);

    // Update jumlah klik
    update_option('ggi_whatsapp_click_count', $current_count + 1);

    // Menghentikan eksekusi AJAX
    wp_die();
}
add_action('wp_ajax_ggi_whatsapp_click', 'ggi_whatsapp_click_counter');
add_action('wp_ajax_nopriv_ggi_whatsapp_click', 'ggi_whatsapp_click_counter');

// Fungsi untuk menambahkan halaman pengaturan di admin
function ggi_whatsapp_chat_settings_menu() {
    add_menu_page(
        'GGi WhatsApp Chat Settings',
        'WhatsApp Chat',
        'manage_options',
        'ggi-whatsapp-chat-settings',
        'ggi_whatsapp_chat_settings_page',
        'dashicons-format-chat',
        100
    );
}
add_action('admin_menu', 'ggi_whatsapp_chat_settings_menu');

// Halaman pengaturan
function ggi_whatsapp_chat_settings_page() {
    if (isset($_POST['save_ggi_whatsapp_settings'])) {
        update_option('ggi_whatsapp_phone', sanitize_text_field($_POST['ggi_whatsapp_phone']));
        update_option('ggi_whatsapp_message', sanitize_textarea_field($_POST['ggi_whatsapp_message']));
        echo '<div class="updated"><p>Pengaturan berhasil disimpan.</p></div>';
    }

    // Ambil pengaturan nomor dan pesan
    $phone = get_option('ggi_whatsapp_phone', '6281277222991');
    $message = get_option('ggi_whatsapp_message', 'Halo, saya ingin bertanya tentang GGi Hotel Batam.');

    // Ambil jumlah klik
    $click_count = get_option('ggi_whatsapp_click_count', 0);
    ?>
    <div class="wrap">
        <h1>Pengaturan GGi WhatsApp Chat</h1>
        <form method="POST">
            <table class="form-table">
                <tr>
                    <th><label for="ggi_whatsapp_phone">Nomor WhatsApp</label></th>
                    <td><input type="text" id="ggi_whatsapp_phone" name="ggi_whatsapp_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="ggi_whatsapp_message">Pesan Default</label></th>
                    <td><textarea id="ggi_whatsapp_message" name="ggi_whatsapp_message" rows="3" class="large-text"><?php echo esc_textarea($message); ?></textarea></td>
                </tr>
            </table>
            <p><input type="submit" name="save_ggi_whatsapp_settings" class="button button-primary" value="Simpan Pengaturan"></p>
        </form>

        <h2>Statistik Klik</h2>
        <p>Total klik pada tombol WhatsApp: <strong><?php echo $click_count; ?></strong></p>
    </div>
    <?php
}

// Fungsi untuk menambahkan counter klik di frontend
function ggi_whatsapp_dynamic_button() {
    $phone_number = get_option('ggi_whatsapp_phone', '6281277222991');
    $message = get_option('ggi_whatsapp_message', 'Halo, saya ingin bertanya tentang GGi Hotel Batam.');

    echo '<div id="ggi-whatsapp-chat" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
        <a href="https://wa.me/' . $phone_number . '?text=' . urlencode($message) . '" target="_blank" style="text-decoration: none;" onclick="ggiRecordClick()">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="Chat WhatsApp" style="width: 60px; height: 60px; border-radius: 50%; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        </a>
    </div>
    <script>
        function ggiRecordClick() {
            fetch("' . admin_url('admin-ajax.php') . '?action=ggi_whatsapp_click");
        }
    </script>';
}
add_action('wp_footer', 'ggi_whatsapp_dynamic_button');
