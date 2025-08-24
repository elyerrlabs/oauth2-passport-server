<template>
    <div>
        <!-- Newsletter -->
        <q-card flat class="bg-primary text-white q-mb-lg">
            <q-card-section class="text-center">
                <div class="text-h5 q-mb-sm">Subscribe to our Newsletter</div>
                <div class="text-subtitle1 q-mb-md">
                    Get the latest updates on new products and upcoming sales
                </div>
                <div class="row justify-center">
                    <div class="col-md-6 col-sm-8 col-xs-12">
                        <q-input
                            v-model="email"
                            outlined
                            bg-color="white"
                            placeholder="Your email address"
                            class="q-mb-sm"
                        >
                            <template v-slot:append>
                                <q-btn
                                    color="orange"
                                    label="Subscribe"
                                    @click="subscribeNewsletter"
                                />
                            </template>
                        </q-input>
                    </div>
                </div>
            </q-card-section>
        </q-card>
    </div>
</template>
<script>
export default {};
</script>
<style lang=""></style>
