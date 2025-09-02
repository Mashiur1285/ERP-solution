// resources/js/app.js
import '../css/app.css'
import './bootstrap'

import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createApp, h } from 'vue'
import { ZiggyVue } from 'ziggy-js'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

// Global error handler for Vue
const handleError = (error, instance, info) => {
  console.error('Vue Error:', error)
  console.error('Component:', instance)
  console.error('Error Info:', info)
  
  // Log specific authentication errors
  if (error.message?.includes('password.request')) {
    console.error('Authentication form error - missing password.request object')
  }
}

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => {
    try {
      return resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'))
    } catch (error) {
      console.error('Failed to resolve page component:', name, error)
      throw error
    }
  },
  setup({ el, App, props, plugin }) {
    const app = createApp({ 
      render: () => h(App, props),
      errorCaptured: handleError
    })

    // Global error handler
    app.config.errorHandler = handleError

    // Global properties for safer access
    app.config.globalProperties.$safeAccess = (obj, path, defaultValue = null) => {
      return path.split('.').reduce((current, key) => {
        return current && current[key] !== undefined ? current[key] : defaultValue
      }, obj)
    }

    return app
      .use(plugin)
      .use(ZiggyVue, { ...(window.Ziggy || {}), location: window.location })
      .mount(el)
  },
  progress: { 
    color: '#4B5563',
    showSpinner: true 
  },
})

// Enhanced global error handlers
window.addEventListener('unhandledrejection', event => {
  console.error('Unhandled promise rejection:', event.reason)
  
  // Handle specific authentication errors
  if (event.reason?.response?.status === 422) {
    console.warn('Validation error occurred')
  }
  
  if (event.reason?.response?.status === 401) {
    console.warn('Authentication error - redirecting to login')
    window.location.href = '/login'
  }
})

window.addEventListener('error', event => {
  console.error('Global JavaScript error:', event.error)
})

// Add Inertia error handler
import { router } from '@inertiajs/vue3'

router.on('error', (errors) => {
  console.error('Inertia navigation error:', errors)
})

router.on('invalid', (event) => {
  console.error('Inertia invalid event:', event)
})