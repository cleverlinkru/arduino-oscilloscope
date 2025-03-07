<script setup>
import {nextTick, onMounted, ref} from "vue";

const props = defineProps([
  'modelValue',
  'id',
  'label',
]);

const emits = defineEmits([
  'update:modelValue',
]);

const val = ref(false);

const handleChange = () => {
  sessionStorage.setItem(props.id, val.value ? 1 : 0);
  emits('update:modelValue', val.value);
};

onMounted(() => {
  val.value = (sessionStorage.getItem(props.id) > 0) ?? true;
  handleChange();
});
</script>

<template>
  <label>
    <input type="checkbox" v-model="val" @change="handleChange"/>
    {{props.label}}
  </label>
</template>
