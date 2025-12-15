<template>
  <div class="partner-section bg pt-50 pb-50">
    <div class="container">
      <div class="partner-wrapper partner-slider owl-carousel owl-theme">
        <img src="https://upload.wikimedia.org/wikipedia/commons/9/9a/Kementerian_Agama_new_logo.png" alt="Kemenag">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg/1200px-Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg.png" alt="Kemendikbud">
        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjsiEi0VHQWUa-4k5SRiCP7Gw_9rQ97Qzcz2FfTleLkYo1-hvWVpLgf78tmi2gZAQfDW0ip_pe-Pda_xrG1yRJWOLOBnMF8t49yyYwI_F0gj5nWgjCMA3yl6_F8trjDkpcbt0loDmW8tqHIRJeFGXy1yZrV-e4PUaRoErmpZXxiUyzhSTiN6JnI0GUvVIk/s1600/rdmkemenag.id.png" alt="RDM Kemenag">
        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEh734d0l2uQHOloBZvvEJrzNxOlVy2xtQAe2e1UUeyAOymJEqNTztdB1wjOoeJUuOMalaj2ebRRmWNLflCpidw5VBAn5JIeb93xO84xlB6kgHf_cBwdxwhlr1DSE_jHkeRgDXZXjamGJNWNBe80YpQMTCItxINQyVcrEX7_gkQiveBkSHJ1wb0J4r2_QHY/s744/emis-logo.55eeefa2.png" alt="EMIS">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/64/Logo_Persis.png/1024px-Logo_Persis.png" alt="Persis">
        <img :src="asset('assets/img/partner/logomts.png')" alt="MTs">
      </div>
    </div>
  </div>
</template>

<script setup>
const asset = (path) => {
  return `/${path}`
}
</script>