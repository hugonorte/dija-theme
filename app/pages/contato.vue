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
const whatsappValue = ref('')
const faxValue = ref('') // Honeypot
const messageValue = ref('')

/**
 * Controle de erro síncrono.
 */
const showErrors = ref({
  name: false,
  email: false,
  whatsapp: false,
  message: false
})

/**
 * Regra do projeto: defineField.
 */
const { defineField, resetForm } = useForm()
const [name] = defineField('name')
const [email] = defineField('email')
const [whatsapp] = defineField('whatsapp')
const [message] = defineField('message')

// Sincronização e Máscara de WhatsApp
watch(whatsappValue, (v) => {
  let val = v.replace(/\D/g, '') // Remove tudo que não é dígito
  if (val.length > 11) val = val.slice(0, 11) // Limita a 11 dígitos

  let masked = val
  if (val.length > 0) masked = '(' + val
  if (val.length > 2) masked = '(' + val.slice(0, 2) + ') ' + val.slice(2)
  if (val.length > 7) masked = '(' + val.slice(0, 2) + ') ' + val.slice(2, 7) + '-' + val.slice(7)

  whatsappValue.value = masked
  whatsapp.value = masked
})

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
  showErrors.value.whatsapp = whatsappValue.value.replace(/\D/g, '').length < 11
  showErrors.value.message = !messageValue.value.trim()

  if (showErrors.value.name || showErrors.value.email || showErrors.value.whatsapp || showErrors.value.message) {
    return
  }

  loading.value = true
  try {
    await submitContactForm({
      name: nameValue.value,
      email: emailValue.value,
      whatsapp: whatsappValue.value,
      message: messageValue.value,
      fax_number: faxValue.value // Envia o valor (deve estar vazio)
    })

    toast.add({
      title: 'Sucesso!',
      description: 'Mensagem enviada.',
      color: 'success'
    })

    nameValue.value = ''
    emailValue.value = ''
    whatsappValue.value = ''
    messageValue.value = ''
    showErrors.value = { name: false, email: false, whatsapp: false, message: false }
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
                for="contact-whatsapp"
                class="label"
              >Seu WhatsApp</label>
              <input
                id="contact-whatsapp"
                v-model="whatsappValue"
                type="text"
                name="whatsapp"
                placeholder="(99) 99999-9999"
                class="input-field"
              >
              <span
                v-show="showErrors.whatsapp"
                class="error-text"
              >Número de WhatsApp incompleto</span>
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

            <div
              class="hp-container"
              aria-hidden="true"
            >
              <label for="fax_number">Fax Number</label>
              <input
                id="fax_number"
                v-model="faxValue"
                type="text"
                name="fax_number"
                autocomplete="off"
                tabindex="-1"
              >
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
  background: rgba(23, 31, 51, 0.45);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 178, 190, 0.08);
  border-radius: 1.5rem; /* radius-lg */
  box-shadow: 0 18px 36px rgba(6, 14, 32, 0.4);
}

.form-title {
  font-size: 1.75rem;
  font-weight: 700;
  margin-bottom: 2rem;
  color: var(--ui-neutral-50);
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
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: var(--ui-neutral-400);
}

.input-field {
  width: 100%;
  padding: 0.875rem 0.25rem;
  background: transparent;
  border: none;
  border-bottom: 2px solid rgba(255, 178, 190, 0.15);
  border-radius: 0;
  color: var(--ui-neutral-50);
  font-size: 1rem;
  transition: all 0.3s ease;
  
  &:focus {
    outline: none;
    border-bottom-color: var(--ui-primary-500);
    box-shadow: 0 1px 0 var(--ui-primary-500);
  }
}

.textarea-field { min-height: 120px; resize: vertical; }

.error-text {
  color: #ffb4ab; /* error color in DESIGN.md */
  font-size: 0.85rem;
  font-weight: 600;
  margin-top: 0.25rem;
}

.actions { display: flex; justify-content: flex-end; }

.submit-button {
  padding: 1rem 3rem;
  background: linear-gradient(135deg, #ffb2be 0%, #ff4e7c 100%);
  color: #660025; /* on-primary */
  border: none;
  border-radius: 9999px;
  font-weight: 800;
  font-size: 1.125rem;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(255, 78, 124, 0.2);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  
  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(255, 78, 124, 0.35);
    filter: brightness(1.05);
  }
  
  &:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
  }
}

.info-sidebar { flex: 1; }
.hp-container {
  position: absolute;
  left: -9999px;
  top: -9999px;
  height: 0;
  width: 0;
  overflow: hidden;
  visibility: hidden;
}
</style>
