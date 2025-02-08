<div class="category">
    <span class="active" data-filter="all">
        <?php
        echo esc_html__('All', 'textdomain'); ?>
    </span>
	<?php
	foreach ($categories as $category): ?>
        <span data-filter="<?php
		echo esc_attr($category->slug); ?>">
            <?php
            echo esc_html($category->name); ?>
        </span>
	<?php
	endforeach; ?>
</div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        const categorySpans = document.querySelectorAll('.category span');

        categorySpans.forEach(span => {
            span.addEventListener('click', function (event) {
                event.preventDefault();

                categorySpans.forEach(item => item.classList.remove('active'));

                this.classList.add('active');

                const selectedCategory = this.getAttribute('data-filter');
                console.log("Clicked:", selectedCategory);

                document.querySelectorAll('.news .item, .news .item-top').forEach(post => {
                    if (selectedCategory === 'all' || post.getAttribute('data-category') === selectedCategory) {
                        post.style.display = 'block';
                    } else {
                        post.style.display = 'none';
                    }
                });
            });
        });
    });
</script>