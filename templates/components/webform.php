<?php
/** @var array $localeContent */
/** @var array $settings */

$inputFields = $localeContent['InputField'] ?? [];
$formText = $localeContent['FormText'] ?? '';
$formConfirmation = $localeContent['FormConfirmation'] ?? '';
$formCaptcha = $localeContent['FormCaptcha'] ?? null;
?>

<section class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
    <?php if (!empty($localeContent['Title'])): ?>
        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">
            <?php echo htmlspecialchars($localeContent['Title']); ?>
        </h2>
    <?php endif; ?>
    
    <?php if (!empty($formText)): ?>
        <div class="prose dark:prose-invert mb-6">
            <?php echo $formText; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="?action=submit-form" class="space-y-6">
        <?php foreach ($inputFields as $index => $field): ?>
            <?php
            $fieldType = $field['FieldType'] ?? 'text';
            $fieldLabel = $field['FieldLabel'] ?? 'Field';
            $fieldMandatory = $field['FieldMandatory'] ?? false;
            $fieldError = $field['FieldErrorMessage'] ?? 'This field is required';
            $fieldValidation = $field['FieldValidation'] ?? '';
            ?>
            
            <div>
                <label for="field-<?php echo $index; ?>" 
                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <?php echo htmlspecialchars($fieldLabel); ?>
                    <?php if ($fieldMandatory): ?>
                        <span class="text-red-500">*</span>
                    <?php endif; ?>
                </label>
                
                <?php if ($fieldType === 'textarea'): ?>
                    <textarea 
                        id="field-<?php echo $index; ?>" 
                        name="field[<?php echo $index; ?>]" 
                        rows="5"
                        <?php echo $fieldMandatory ? 'required' : ''; ?>
                        <?php if ($fieldValidation): ?>
                            pattern="<?php echo htmlspecialchars($fieldValidation); ?>"
                        <?php endif; ?>
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                        placeholder="<?php echo htmlspecialchars($fieldLabel); ?>"
                    ></textarea>
                <?php else: ?>
                    <input 
                        type="<?php echo htmlspecialchars($fieldType); ?>" 
                        id="field-<?php echo $index; ?>" 
                        name="field[<?php echo $index; ?>]" 
                        <?php echo $fieldMandatory ? 'required' : ''; ?>
                        <?php if ($fieldValidation): ?>
                            pattern="<?php echo htmlspecialchars($fieldValidation); ?>"
                        <?php endif; ?>
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="<?php echo htmlspecialchars($fieldLabel); ?>"
                    >
                <?php endif; ?>
                
                <?php if ($fieldMandatory): ?>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden field-error" data-field="<?php echo $index; ?>">
                        <?php echo htmlspecialchars($fieldError); ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        
        <?php if ($formCaptcha): ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <?php echo htmlspecialchars($formCaptcha['FieldLabel'] ?? 'Security Check'); ?> *
                </label>
                <div class="flex items-center gap-4">
                    <div class="bg-gray-100 dark:bg-gray-700 px-6 py-3 rounded-lg font-mono text-2xl">
                        <?php
                        // Simple captcha (in production use a proper captcha library)
                        $num1 = rand(1, 10);
                        $num2 = rand(1, 10);
                        $captchaAnswer = $num1 + $num2;
                        echo "$num1 + $num2 = ?";
                        ?>
                    </div>
                    <input 
                        type="number" 
                        name="captcha" 
                        required
                        class="w-24 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <input type="hidden" name="captcha_answer" value="<?php echo $captchaAnswer; ?>">
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($formConfirmation)): ?>
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="prose prose-sm dark:prose-invert">
                    <?php echo $formConfirmation; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div>
            <button 
                type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                Invia Messaggio
            </button>
        </div>
    </form>
    
    <!-- Success/Error Messages -->
    <?php if (isset($_GET['form_status'])): ?>
        <?php if ($_GET['form_status'] === 'success'): ?>
            <div class="mt-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <p class="text-green-800 dark:text-green-200">
                    ✓ Messaggio inviato con successo! Ti risponderemo presto.
                </p>
            </div>
        <?php elseif ($_GET['form_status'] === 'error'): ?>
            <div class="mt-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <p class="text-red-800 dark:text-red-200">
                    ✗ Errore nell'invio del messaggio. Riprova più tardi.
                </p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</section>

<script>
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        let isValid = true;
        const requiredFields = this.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            const errorMsg = this.querySelector(`.field-error[data-field="${field.name.match(/\d+/)?.[0]}"]`);
            if (field.value.trim() === '') {
                isValid = false;
                if (errorMsg) {
                    errorMsg.classList.remove('hidden');
                }
                field.classList.add('border-red-500');
            } else {
                if (errorMsg) {
                    errorMsg.classList.add('hidden');
                }
                field.classList.remove('border-red-500');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
        }
    });
</script>
