<?php
/**
 * Template for displaying course materials
 * Path: iddi_academy/learnpress/single-course/materials.php
 */

defined( 'ABSPATH' ) || exit;

$item = LP_Global::course_item();
if ( ! $item ) return;

$item_id = $item->get_id();
$material_db = LP_Material_Files_DB::getInstance();
$material_files = $material_db->get_material_by_item_id( $item_id, -1, 0, false );

if ( empty( $material_files ) ) return;
?>

<div class="iddi-materials-section">
    <div class="iddi-materials-header d-flex flex-ai-center gap-s margin-bottom-m">
        <span class="iddi-materials-icon">
            <svg  viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M18 2.01562H9.98438L7.96875 0H1.96875C0.890625 0 0 0.9375 0 2.01562V14.0156C0 15.0938 0.890625 16.0312 1.96875 16.0312H18C19.0781 16.0312 19.9688 15.0938 19.9688 14.0156V4.03125C19.9688 2.90625 19.0781 2.01562 18 2.01562ZM18 14.0156H1.96875V4.03125H18V14.0156Z" fill="#E65C23"/>
</svg>

        </span>
        <h3 class="iddi-materials-title fs-20 fw-700 text-color-oxford-blue">Tài liệu bài học</h3>
    </div>
    
    <div class="iddi-materials-grid">
        <?php foreach ( $material_files as $m ) : 
            $file_size = '---';
            if ( $m->method == 'upload' ) {
                $full_path = wp_upload_dir()['basedir'] . $m->file_path;
                if ( file_exists( $full_path ) ) {
                    $size = filesize( $full_path );
                    $file_size = ( $size / 1024 < 1024 ) ? round( $size / 1024, 2 ) . ' MB' : round( $size / 1024 / 1024, 2 ) . ' MB';
                    // Đảm bảo đơn vị MB như trong ảnh
                    if ($size / 1024 < 1024) {
                        $file_size = round( $size / 1024 / 1024, 2 ) . ' MB';
                    }
                }
            } else {
                $file_size = 'Link';
            }
            
            $file_url = ( $m->method == 'upload' ) ? wp_upload_dir()['baseurl'] . $m->file_path : $m->file_path;
            $file_ext = strtoupper($m->file_type);
            
            // Xác định màu sắc dựa trên định dạng
            $icon_bg = ($file_ext === 'PDF') ? '#FEE2E2' : '#E0F2FE';
            $icon_color = ($file_ext === 'PDF') ? '#EF4444' : '#3B82F6';
        ?>
            <div class="iddi-material-card">
                <div class="material-card-inner d-flex flex-ai-center">
                    <div class="material-icon-wrap" style="background: <?php echo $icon_bg; ?>;">
                        <?php if ($file_ext === 'PDF') : ?>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="<?php echo $icon_color; ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <?php else : ?>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="<?php echo $icon_color; ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                        <?php endif; ?>
                    </div>
                    
                    <div class="material-info flex-1">
                        <h4 class="material-name fs-16 fw-600 text-color-oxford-blue"><?php echo esc_html( $m->file_name ); ?></h4>
                        <p class="material-meta fs-13 text-color-cool-gray">
                            <?php echo $file_size; ?> • <?php echo $file_ext; ?> DOCUMENT
                        </p>
                    </div>
                    
                    <a href="<?php echo esc_url( $file_url ); ?>" target="_blank" rel="nofollow" class="material-download-link">
                        <svg width="14" height="18" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M13.9688 6H9.98438V0H3.98438V6H0L6.98438 12.9844L13.9688 6ZM6 8.01562V2.01562H7.96875V8.01562H9.14062L6.98438 10.1719L4.82812 8.01562H6ZM0 15H13.9688V17.0156H0V15Z" fill="#94A3B8"/>
</svg>

                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
