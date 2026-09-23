<?php
/**
 * Custom payment gateway for LearnPress using Vietnamese Bank Transfer (VietQR)
 * File: inc/class-lp-gateway-iddi-bank.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'LP_Gateway_Abstract' ) && class_exists( 'LP_Gateway' ) ) {
    class_alias( 'LP_Gateway', 'LP_Gateway_Abstract' );
}

class LP_Gateway_IDDI_Bank extends LP_Gateway_Abstract {
    
    /**
     * LP_Gateway_IDDI_Bank Constructor.
     */
    public function __construct() {
        $this->id = 'iddi_bank';
        $this->method_title = 'Chuyển khoản Ngân hàng (IDDI)';
        $this->method_description = 'Thanh toán học phí bằng cách chuyển khoản ngân hàng trực tiếp qua mã VietQR.';
        $this->icon = '';
        
        parent::__construct();

        // Get settings
        $this->title       = $this->settings->get( 'title', $this->method_title );
        $this->description = $this->settings->get( 'description', $this->method_description );
        $this->enabled     = $this->settings->get( 'enable', 'no' );
    }
    
    /**
     * Define settings fields for LearnPress admin dashboard.
     */
    public function get_settings() {
        return array(
            array(
                'type' => 'title',
            ),
            array(
                'title'   => __( 'Kích hoạt', 'learnpress' ),
                'id'      => '[enable]',
                'default' => 'no',
                'type'    => 'checkbox',
            ),
            array(
                'title'   => __( 'Tiêu đề', 'learnpress' ),
                'id'      => '[title]',
                'default' => 'Chuyển khoản Ngân hàng (IDDI)',
                'type'    => 'text',
            ),
            array(
                'title'   => __( 'Mô tả', 'learnpress' ),
                'id'      => '[description]',
                'default' => 'Thanh toán học phí bằng cách chuyển khoản ngân hàng trực tiếp qua mã VietQR.',
                'type'    => 'textarea',
            ),
            array(
                'title'   => __( 'Tên chủ tài khoản', 'learnpress' ),
                'id'      => '[account_name]',
                'default' => 'Công Ty Cổ Phần Học Viện Quốc Tế Nha Khoa Và Đổi Mới Số',
                'type'    => 'text',
            ),
            array(
                'title'   => __( 'Ngân hàng', 'learnpress' ),
                'id'      => '[bank_name]',
                'default' => 'BIDV Chợ Lớn',
                'type'    => 'text',
            ),
            array(
                'title'   => __( 'Mã ngân hàng (cho VietQR)', 'learnpress' ),
                'id'      => '[bank_code]',
                'default' => 'bidv',
                'type'    => 'text',
                'desc'    => 'Mã viết tắt của ngân hàng để tạo mã VietQR.<br>' .
                             '<strong>Các mã phổ biến:</strong><br>' .
                             '- <code>bidv</code> (BIDV)<br>' .
                             '- <code>vcb</code> hoặc <code>vietcombank</code> (Vietcombank)<br>' .
                             '- <code>tcb</code> hoặc <code>techcombank</code> (Techcombank)<br>' .
                             '- <code>mbbank</code> hoặc <code>mb</code> (MB Bank)<br>' .
                             '- <code>acb</code> (ACB)<br>' .
                             '- <code>vietinbank</code> (VietinBank)<br>' .
                             '- <code>vpbank</code> (VPBank)<br>' .
                             '- <code>tpbank</code> (TPBank)<br>' .
                             '- <code>shb</code> (SHB)<br>' .
                             '- <code>hdbank</code> (HDBank)<br>' .
                             '- <code>sacombank</code> (Sacombank)<br>' .
                             '- <code>vib</code> (VIB)<br>' .
                             '- <code>msb</code> (MSB)<br>' .
                             '- <code>agribank</code> (Agribank)<br>' .
                             '- <code>ocb</code> (OCB)<br>' .
                             '- <code>eximbank</code> (Eximbank)<br>' .
                             '- <code>seabank</code> (SeABank)<br>' .
                             '- <code>lienvietpostbank</code> (LPBank)<br>' .
                             '<em>(Bạn cũng có thể nhập trực tiếp mã BIN 6 số của ngân hàng, ví dụ: 970418).</em>',
            ),
            array(
                'title'   => __( 'Số tài khoản', 'learnpress' ),
                'id'      => '[account_no]',
                'default' => '8640080519',
                'type'    => 'text',
            ),
            array(
                'title'   => __( 'Webhook Token bảo mật', 'learnpress' ),
                'id'      => '[webhook_token]',
                'default' => 'iddi_sec_token_2026',
                'type'    => 'text',
            ),
            array(
                'type' => 'sectionend',
            ),
        );
    }
    
    /**
     * Show gateway fields on checkout page.
     */
    public function get_payment_form() {
        $acc_name  = $this->settings->get( 'account_name', 'Công Ty Cổ Phần Học Viện Quốc Tế Nha Khoa Và Đổi Mới Số' );
        $bank_name = $this->settings->get( 'bank_name', 'BIDV Chợ Lớn' );
        $acc_no    = $this->settings->get( 'account_no', '8640080519' );
        
        $display_name = 'Guest';
        if ( is_user_logged_in() ) {
            $user = wp_get_current_user();
            $display_name = $user->display_name;
        }
        
        $course_name = 'Khoa hoc';
        $cart = learn_press_get_cart();
        if ( $cart ) {
            $cart_items = $cart->get_items();
            if ( $cart_items ) {
                $item_names = array();
                foreach ( $cart_items as $item ) {
                    $course_id = isset( $item['item_id'] ) ? $item['item_id'] : (isset($item['course_id']) ? $item['course_id'] : 0);
                    if ( $course_id ) {
                        $item_names[] = get_the_title( $course_id );
                    }
                }
                if ( ! empty( $item_names ) ) {
                    $course_name = implode( ', ', $item_names );
                }
            }
        }
        
        $clean_display_name = function_exists( 'iddi_remove_vietnamese_tones' ) ? iddi_remove_vietnamese_tones( $display_name ) : $display_name;
        $clean_course_name = function_exists( 'iddi_remove_vietnamese_tones' ) ? iddi_remove_vietnamese_tones( $course_name ) : $course_name;
        
        $raw_memo = $clean_display_name . ' ' . strtoupper( $clean_course_name );
        $memo = substr( $raw_memo, 0, 90 );
        
        ob_start();
        ?>
        <div class="lp-gateway-iddi-bank-form" style="padding: 15px; background: #fafafa; border: 1px solid #e5e5e5; border-radius: 8px; margin-top: 10px;">
            <p style="margin-top: 0; font-weight: 600; color: #1a1a1a; display: flex; align-items: center; gap: 8px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #ff6600;"><rect x="2" y="5" width="20" height="14" rx="2" ry="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                Thông tin thanh toán chuyển khoản:
            </p>
            <ul style="list-style: none; padding-left: 0; margin-bottom: 0; line-height: 1.6; color: #4a4a4a;">
                <li style="margin-bottom: 4px;"><strong>Chủ tài khoản:</strong> <?php echo esc_html( $acc_name ); ?></li>
                <li style="margin-bottom: 4px;"><strong>Ngân hàng:</strong> <?php echo esc_html( $bank_name ); ?></li>
                <li style="margin-bottom: 4px;"><strong>Số tài khoản:</strong> <?php echo esc_html( $acc_no ); ?></li>
                <li style="margin-bottom: 4px;"><strong>Nội dung bắt buộc:</strong> <?php echo esc_html( $memo ); ?></li>
            </ul>
            <p style="font-size: 13px; color: #777; margin-top: 10px; margin-bottom: 0; font-style: italic;">
                * Mã QR chứa sẵn số tiền và nội dung chuyển khoản tự động sẽ xuất hiện sau khi quý bác sĩ bấm "Đặt hàng".
            </p>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Process payment.
     *
     * @param int $order_id
     * @return array
     */
    public function process_payment( $order_id ) {
        $order = learn_press_get_order( $order_id );
        
        // Update order status to Pending
        $order->update_status( 'pending' );
        
        // Empty cart
        $cart = learn_press_get_cart();
        if ( $cart ) {
            $cart->empty_cart();
        }
        
        return array(
            'result'   => 'success',
            'redirect' => $this->get_return_url( $order )
        );
    }
}
