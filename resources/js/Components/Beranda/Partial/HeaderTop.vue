<!-- components/HeaderTop.vue -->
<template>
  <div class="header-top">
    <div class="container">
      <div class="header-top-wrap">
        <div class="header-top-left">
          <div class="header-top-social">
            <span>Follow Us: </span>
            <a :href="$page.props.lembaga?.facebook || '#'" target="_blank" aria-label="Facebook" v-if="$page.props.lembaga?.facebook">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a :href="$page.props.lembaga?.instagram || '#'" target="_blank" aria-label="Instagram" v-if="$page.props.lembaga?.instagram">
              <i class="fab fa-instagram"></i>
            </a>
            <a :href="$page.props.lembaga?.twitter || '#'" target="_blank" aria-label="X" v-if="$page.props.lembaga?.twitter">
              <i class="fab fa-twitter"></i>
            </a>
            <a :href="'https://wa.me/' + ($page.props.lembaga?.whatsapp || '').replace(/[^0-9]/g, '')" target="_blank" aria-label="WhatsApp" v-if="$page.props.lembaga?.whatsapp">
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

<style scoped>
/* Tambahkan styling sesuai kebutuhan */
.truncate {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 200px;
  display: inline-block;
}

@media (max-width: 768px) {
  .truncate {
    max-width: 150px;
  }
}
</style>