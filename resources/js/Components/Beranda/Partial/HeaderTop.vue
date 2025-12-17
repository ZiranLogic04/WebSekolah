<!-- components/HeaderTop.vue -->
<template>
  <div class="header-top">
    <div class="container">
      <div class="header-top-wrap">
        <div class="header-top-left">
          <div class="header-top-social">
            <span>Follow Us: </span>
            <a href="https://www.facebook.com/" target="_blank" aria-label="Facebook">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://www.instagram.com/" target="_blank" aria-label="Instagram">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="https://x.com/" target="_blank" aria-label="X">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" target="_blank" aria-label="WhatsApp">
              <i class="fab fa-whatsapp"></i>
            </a>
          </div>
        </div>

        <div class="header-top-right">
          <div class="header-top-contact">
            <ul class="ht-contact">
              <li class="ht-item ht-item--loc">
                <i class="fa fa-location-dot"></i>
                <a 
                  :href="'https://maps.google.com/?q=' + ($page.props.lembaga?.alamat ?? '')"
                  class="truncate" 
                  :title="$page.props.lembaga?.alamat || 'Alamat Belum Diisi'">
                  {{ $page.props.lembaga?.alamat || 'Alamat Belum Diisi' }}
                </a>
                <a 
                  :href="'https://maps.google.com/?q=' + ($page.props.lembaga?.alamat ?? '')"
                  class="map-link">Peta</a>
              </li>

              <li class="ht-item">
                <i class="far fa-envelope"></i>
                <a :href="'mailto:' + ($page.props.lembaga?.email || '')" class="truncate" :title="$page.props.lembaga?.email">
                  {{ $page.props.lembaga?.email || 'Email Belum Diisi' }}
                </a>
              </li>

              <li class="ht-item">
                <i class="fa fa-phone-volume"></i>
                <a :href="'tel:' + ($page.props.lembaga?.telepon || '')" class="truncate" :title="$page.props.lembaga?.telepon">
                   {{ $page.props.lembaga?.telepon || 'No. Telp Belum Diisi' }}
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Komponen tidak memerlukan logic tambahan untuk sekarang
</script>

<style>
/* Force styling for social icons to ensure visibility */
.header-top-social span {
    color: #ffffff;
    font-weight: 500;
}

.header-top-social a {
    width: 35px;
    height: 35px;
    line-height: 35px;
    color: #ffffff;
    font-size: 16px;
    background: #116E63; /* var(--theme-color) */
    text-align: center;
    margin-left: 5px;
    border-radius: 50%;
    transition: all .5s ease-in-out;
    display: inline-block;
}

.header-top-social a:hover {
    color: #FDA31B; /* var(--theme-color2) */
}
</style>