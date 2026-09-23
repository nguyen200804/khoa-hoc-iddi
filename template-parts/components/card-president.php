<?php
/**
 * Component: President Card
 * 
 * @param array $args {
 *     @type string $tag   Tag text (e.g., 'DDA GLOBAL', 'PODCAST GUEST')
 *     @type string $img   Image URL or attachment ID
 *     @type string $flag  Flag emoji or image URL
 *     @type string $role  Role description
 *     @type string $name  Person name
 *     @type string $desc  Short description/bio
 *     @type array  $socials Social links [ ['icon' => 'facebook', 'url' => '#'], ... ]
 * }
 */

$tag    = $args['tag'] ?? 'DDA GLOBAL';
$img    = $args['img'] ?? '';
$flag   = $args['flag'] ?? '';
$role   = $args['role'] ?? '';
$name   = $args['name'] ?? '';
$desc   = $args['desc'] ?? '';
$socials = $args['socials'] ?? [];
?>

<div class="iddi-card-president">
    <div class="iddi-card-president__thumb">
        <?php if ($tag): ?>
            <div class="iddi-card-president__tag"><?php echo esc_html($tag); ?></div>
        <?php endif; ?>
        
        <?php if ($img): ?>
            <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($name); ?>" class="iddi-card-president__img">
        <?php endif; ?>

        <?php if ($flag): ?>
            <div class="iddi-card-president__flag">
                <?php if (filter_var($flag, FILTER_VALIDATE_URL)): ?>
                    <img src="<?php echo esc_url($flag); ?>" alt="Flag">
                <?php else: ?>
                    <span><?php echo esc_html($flag); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="iddi-card-president__body">
        <?php if ($role): ?>
            <p class="iddi-card-president__role"><?php echo esc_html($role); ?></p>
        <?php endif; ?>

        <?php if ($name): ?>
            <h3 class="iddi-card-president__name"><?php echo esc_html($name); ?></h3>
        <?php endif; ?>

        <?php if ($desc): ?>
            <p class="iddi-card-president__desc"><?php echo esc_html($desc); ?></p>
        <?php endif; ?>

        <div class="iddi-card-president__social">
            <?php foreach ($socials as $social): ?>
                <a href="<?php echo esc_url($social['url']); ?>" target="_blank" rel="noopener noreferrer">
                    <?php 
                    if (isset($social['icon'])) {
                        echo get_my_svg($social['icon']); 
                    }
                    ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
