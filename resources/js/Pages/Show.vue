<script setup>
import {computed, reactive, ref} from 'vue'
import {Chart, Grid, Line, Tooltip} from 'vue3-charts'
import MemoryCheckbox from "@/Components/MemoryCheckbox.vue";
import {Form, FormItem, Input, Button} from "ant-design-vue";

const props = defineProps([
  'data',
  'lastPing',
  'lastLoad',
  'stepCount',
]);

const data = ref(props.data);
const direction = ref('horizontal');
const margin = ref({
  left: 0,
  top: 20,
  right: 20,
  bottom: 0
});
const axis = ref({
  primary: {
    type: 'band'
  },
  secondary: {
    domain: ['0', '6000'],
    type: 'linear',
    ticks: 8
  }
});

const chartWidth = computed(() => {
  return data.value.length * 50;
});

const showP1 = ref(true);
const showP2 = ref(true);
const showP3 = ref(true);
const showP4 = ref(true);
const showP5 = ref(true);
const showP6 = ref(true);
const showP7 = ref(true);
const showP8 = ref(true);

const loading = ref(false);

const form = reactive({
  step: sessionStorage.getItem('step') ?? 1000,
  stepCount: sessionStorage.getItem('stepCount') ?? 10,
});

const handleLoad = () => {
  loading.value = true;

  sessionStorage.setItem('step', form.step);
  sessionStorage.setItem('stepCount', form.stepCount);

  axios.post('/api/load', form)
    .then(() => {
      loading.value = false;
    });
};
</script>

<template>
  <div class="page">
    <div>Last ping: {{ props.lastPing }} sec</div>
    <div>Last load: {{ props.lastLoad }} sec</div>
    <div>Step count: {{ props.stepCount }}</div>

    <div>
      <MemoryCheckbox v-model="showP1" id="p1" label="p1"/>
      <MemoryCheckbox v-model="showP2" id="p2" label="p2"/>
      <MemoryCheckbox v-model="showP3" id="p3" label="p3"/>
      <MemoryCheckbox v-model="showP4" id="p4" label="p4"/>
      <MemoryCheckbox v-model="showP5" id="p5" label="p5"/>
      <MemoryCheckbox v-model="showP6" id="p6" label="p6"/>
      <MemoryCheckbox v-model="showP7" id="p7" label="p7"/>
      <MemoryCheckbox v-model="showP8" id="p8" label="p8"/>
    </div>

    <div>
      <Form
        :model="form"
        layout="vertical"
        autocomplete="off"
        class="form"
        :disabled="loading"
        @finish="handleLoad"
      >
        <FormItem
          label="Step"
          name="step"
          :rules="[{ required: true, message: 'Required' }]"
        >
          <Input v-model:value="form.step"/>
        </FormItem>
        <FormItem
          label="Step count"
          name="stepCount"
          :rules="[{ required: true, message: 'Required' }]"
        >
          <Input v-model:value="form.stepCount"/>
        </FormItem>
        <FormItem>
          <Button type="primary" html-type="submit">Load</Button>
        </FormItem>
      </Form>
    </div>

    <Chart
      :size="{ width: chartWidth, height: 500 }"
      :data="data"
      :margin="margin"
      :direction="direction"
      :axis="axis">

      <template #layers>
        <Grid strokeDasharray="2,2"/>
        <Line v-if="showP1" :dataKeys="['time', 'p1']"/>
        <Line v-if="showP2" :dataKeys="['time', 'p2']"/>
        <Line v-if="showP3" :dataKeys="['time', 'p3']"/>
        <Line v-if="showP4" :dataKeys="['time', 'p4']"/>
        <Line v-if="showP5" :dataKeys="['time', 'p5']"/>
        <Line v-if="showP6" :dataKeys="['time', 'p6']"/>
        <Line v-if="showP7" :dataKeys="['time', 'p7']"/>
        <Line v-if="showP8" :dataKeys="['time', 'p8']"/>
      </template>

      <template #widgets>
        <Tooltip/>
      </template>

    </Chart>
  </div>
</template>

<style scoped>
.page {
  margin: 0 20px;
}

.form {
  max-width: 300px;
}
</style>
