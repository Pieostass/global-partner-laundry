<?php

namespace Database\Seeders;

use App\Models\SiteConfig;
use Illuminate\Database\Seeder;

class SiteConfigSeeder extends Seeder
{
    public function run(): void
    {
        $configs = [
            // Thương hiệu
            ['config_key' => 'site_name', 'config_value' => 'Global Partner', 'description' => 'Tên trang web'],
            ['config_key' => 'site_tagline', 'config_value' => 'Giặt sạch · Giao nhanh', 'description' => 'Khẩu hiệu'],
            // Giao diện
            ['config_key' => 'background_color', 'config_value' => '#FDFCF8', 'description' => 'Màu nền chính (hex)'],
            ['config_key' => 'primary_color', 'config_value' => '#9C7C52', 'description' => 'Màu chính (bronze)'],
            ['config_key' => 'accent_color', 'config_value' => '#B8976A', 'description' => 'Màu nhấn (bronze nhạt)'],
            ['config_key' => 'navbar_color', 'config_value' => '#0a2540', 'description' => 'Màu thanh điều hướng'],
            // Logo
            ['config_key' => 'logo_url', 'config_value' => '', 'description' => 'Đường dẫn logo (url hoặc /storage/...)'],
            // Hero
            ['config_key' => 'hero_title', 'config_value' => "Giặt Sạch, Giao Nhanh\nTận Cửa Nhà Bạn", 'description' => 'Tiêu đề hero'],
            ['config_key' => 'hero_subtitle', 'config_value' => 'Dịch vụ giặt là chuyên nghiệp, tận tâm và nhanh chóng.', 'description' => 'Phụ đề hero'],
            ['config_key' => 'hero_btn1_text', 'config_value' => 'Mua ngay', 'description' => 'Nút hero 1'],
            ['config_key' => 'hero_btn1_url', 'config_value' => '/shop', 'description' => 'Link nút hero 1'],
            ['config_key' => 'hero_btn2_text', 'config_value' => 'Flash Sale', 'description' => 'Nút hero 2'],
            ['config_key' => 'hero_btn2_url', 'config_value' => '/flash-sale', 'description' => 'Link nút hero 2'],
            // USP
            ['config_key' => 'usp1_icon', 'config_value' => '🧺', 'description' => 'USP 1 icon'],
            ['config_key' => 'usp1_title', 'config_value' => 'Chất lượng cao', 'description' => 'USP 1 tiêu đề'],
            ['config_key' => 'usp1_desc', 'config_value' => 'Sử dụng công nghệ giặt tiên tiến', 'description' => 'USP 1 mô tả'],
            ['config_key' => 'usp2_icon', 'config_value' => '🚚', 'description' => 'USP 2 icon'],
            ['config_key' => 'usp2_title', 'config_value' => 'Giao hàng nhanh', 'description' => 'USP 2 tiêu đề'],
            ['config_key' => 'usp2_desc', 'config_value' => 'Nhận hàng trong 2 giờ', 'description' => 'USP 2 mô tả'],
            ['config_key' => 'usp3_icon', 'config_value' => '💧', 'description' => 'USP 3 icon'],
            ['config_key' => 'usp3_title', 'config_value' => 'Thân thiện môi trường', 'description' => 'USP 3 tiêu đề'],
            ['config_key' => 'usp3_desc', 'config_value' => 'Sản phẩm sinh học', 'description' => 'USP 3 mô tả'],
            ['config_key' => 'usp4_icon', 'config_value' => '🏅', 'description' => 'USP 4 icon'],
            ['config_key' => 'usp4_title', 'config_value' => 'Uy tín hàng đầu', 'description' => 'USP 4 tiêu đề'],
            ['config_key' => 'usp4_desc', 'config_value' => 'Hơn 10 năm kinh nghiệm', 'description' => 'USP 4 mô tả'],
            // Footer
            ['config_key' => 'footer_address', 'config_value' => '123 Đường Láng, Hà Nội', 'description' => 'Địa chỉ footer'],
            ['config_key' => 'footer_phone', 'config_value' => '0901 234 567', 'description' => 'SĐT footer'],
            ['config_key' => 'footer_email', 'config_value' => 'hello@globalpartner.vn', 'description' => 'Email footer'],
            ['config_key' => 'footer_hours', 'config_value' => '8:00 - 20:00 hàng ngày', 'description' => 'Giờ làm việc footer'],
        ];

        foreach ($configs as $cfg) {
            SiteConfig::updateOrCreate(
                ['config_key' => $cfg['config_key']],
                ['config_value' => $cfg['config_value'], 'description' => $cfg['description']]
            );
        }
    }
}