<script setup lang="ts">
import { useForm } from 'vee-validate'

definePageMeta({
  layout: 'contato'
})

const { submitContactForm } = useWordPress()
const toast = useToast()

/**
 * Refs para estado do formulário.
 */
const nameValue = ref('')
const emailValue = ref('')
const messageValue = ref('')

/**
 * Controle de erro síncrono.
 */
const showErrors = ref({
  name: false,
  email: false,
  message: false
})

/**
 * Regra do projeto: defineField.
 */
const { defineField, resetForm } = useForm()
const [name] = defineField('name')
const [email] = defineField('email')
const [message] = defineField('message')

// Sincronização
watch(nameValue, v => name.value = v)
watch(emailValue, v => email.value = v)
watch(messageValue, v => message.value = v)

const loading = ref(false)

/**
 * Handler de submissão.
 * A validação de estado acontece instantaneamente.
 */
const onFormSubmit = async () => {
  // Validação síncrona para o Cypress detectar a mudança de UI imediatamente
  showErrors.value.name = !nameValue.value.trim()
  showErrors.value.email = !emailValue.value.trim() || !/.+@.+\..+/.test(emailValue.value)
  showErrors.value.message = !messageValue.value.trim()

  if (showErrors.value.name || showErrors.value.email || showErrors.value.message) {
    return
  }

  loading.value = true
  try {
    await submitContactForm({
      name: nameValue.value,
      email: emailValue.value,
      message: messageValue.value
    })

    toast.add({
      title: 'Sucesso!',
      description: 'Mensagem enviada.',
      color: 'success'
    })

    nameValue.value = ''
    emailValue.value = ''
    messageValue.value = ''
    showErrors.value = { name: false, email: false, message: false }
    resetForm()
  } catch {
    toast.add({ title: 'Erro', description: 'Falha ao enviar.', color: 'error' })
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <UContainer class="contact-page">
    <UPageHero
      title="Entre em Contato"
      description="Mande uma mensagem para a Dijaina!"
    />

    <div class="contact-layout">
      <!--
        O uso do ClientOnly é CRÍTICO aqui.
        Ele impede que o Nuxt realize a hidratação (SSR -> Client) neste bloco,
        evitando que o Cypress perca a referência dos elementos ou dos listeners
        de evento durante o processo de reconstrução do DOM pelo Vue.
      -->
      <ClientOnly>
        <div class="form-card">
          <h2 class="form-title">
            Envie sua Mensagem
          </h2>

          <form
            class="clean-form"
            @submit.prevent="onFormSubmit"
          >
            <div class="form-group">
              <label
                for="contact-name"
                class="label"
              >Seu Nome</label>
              <input
                id="contact-name"
                v-model="nameValue"
                type="text"
                name="name"
                placeholder="Nome completo"
                class="input-field"
              >
              <!-- v-show garante que o elemento esteja SEMPRE no DOM para o Cypress -->
              <span
                v-show="showErrors.name"
                class="error-text"
              >Nome é obrigatório</span>
            </div>

            <div class="form-group">
              <label
                for="contact-email"
                class="label"
              >Seu Email</label>
              <input
                id="contact-email"
                v-model="emailValue"
                type="email"
                name="email"
                placeholder="email@exemplo.com"
                class="input-field"
              >
              <span
                v-show="showErrors.email"
                class="error-text"
              >Email inválido</span>
            </div>

            <div class="form-group">
              <label
                for="contact-message"
                class="label"
              >Mensagem</label>
              <textarea
                id="contact-message"
                v-model="messageValue"
                name="message"
                placeholder="Sua mensagem..."
                rows="5"
                class="input-field textarea-field"
              />
              <span
                v-show="showErrors.message"
                class="error-text"
              >Mensagem é obrigatória</span>
            </div>

            <div class="actions">
              <button
                type="submit"
                class="submit-button"
                :disabled="loading"
              >
                {{ loading ? 'Enviando...' : 'Enviar Mensagem' }}
              </button>
            </div>
          </form>
        </div>
      </ClientOnly>

      <div class="info-sidebar">
        <UPageCard title="Contato Direto">
          <p>São Paulo, SP</p>
        </UPageCard>
      </div>
    </div>
  </UContainer>
</template>

<style lang="scss" scoped>
.contact-page {
  padding: 3rem 0;
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.contact-layout {
  display: flex;
  gap: 3rem;
  @media (max-width: 900px) { flex-direction: column; }
}

.form-card {
  flex: 2;
  padding: 2.5rem;
  background: var(--ui-bg);
  border: 1px solid var(--ui-border);
  border-radius: var(--ui-radius-lg);
  box-shadow: var(--ui-shadow-md);
}

.form-title {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 2rem;
}

.clean-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.label {
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--ui-text-muted);
}

.input-field {
  width: 100%;
  padding: 0.875rem 1rem;
  background: transparent;
  border: 1px solid var(--ui-border);
  border-radius: var(--ui-radius-md);
  color: var(--ui-text);
  font-size: 1rem;
  &:focus { outline: none; border-color: var(--ui-primary); }
}

.textarea-field { min-height: 150px; resize: vertical; }

.error-text {
  color: #ef4444;
  font-size: 0.85rem;
  font-weight: 600;
  margin-top: 0.25rem;
}

.actions { display: flex; justify-content: flex-end; }

.submit-button {
  padding: 1rem 3rem;
  background: var(--ui-primary);
  color: white;
  border: none;
  border-radius: var(--ui-radius-md);
  font-weight: 700;
  font-size: 1.125rem;
  cursor: pointer;
  transition: opacity 0.2s;
  &:hover { opacity: 0.9; }
  &:disabled { opacity: 0.6; cursor: not-allowed; }
}

.info-sidebar { flex: 1; }
</style>
