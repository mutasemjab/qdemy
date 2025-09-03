/**
 * SubjectFormManager - Manages the subject creation/edit form
 * Native JavaScript, OOP approach, clean and understandable
 */
class SubjectFormManager {
    constructor(config) {
        this.config = config;
        this.form = document.getElementById(config.formId);
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Cache DOM elements
        this.elements = {
            programSelect: document.getElementById('programm_id'),
            gradeSelect: document.getElementById('grade_id'),
            gradeSection: document.getElementById('gradeSection'),
            gradeRequired: document.querySelector('.grade-required'),
            semesterSelect: document.getElementById('semester_id'),
            semesterSection: document.getElementById('semesterSection'),
            fieldTypeSelect: document.getElementById('field_type_select'),
            fieldsSelectionSection: document.getElementById('fieldsSelectionSection'),
            fieldsTableBody: document.getElementById('fieldsTableBody'),
            tawjihiFirstYearSection: document.getElementById('tawjihiFirstYearSection'),
        };

        // Store fetched data
        this.data = {
            grades: [],
            semesters: [],
            fields: []
        };

        this.init();
    }

    /**
     * Initialize the form manager
     */
    async init() {
        this.attachEventListeners();

        // For edit mode, load data sequentially
        if (this.config.isEditMode && this.elements.programSelect.value) {
            await this.loadInitialData();
        }
    }

    /**
     * Load initial data for edit mode
     */
    async loadInitialData() {
        const programId = this.elements.programSelect.value;
        const selectedOption = this.elements.programSelect.selectedOptions[0];
        const programCtgKey = selectedOption.dataset.ctgKey;

        // Load grades if needed
        if (!this.isProgramWithoutGrades(programCtgKey)) {
            this.elements.gradeSection.style.display = 'block';
            this.elements.gradeRequired.style.display = 'inline';
            this.elements.gradeSelect.setAttribute('required', 'required');

            await this.loadGrades(programId);

            // Restore grade selection if exists
            if (this.config.existingData.gradeId) {
                this.elements.gradeSelect.value = this.config.existingData.gradeId;

                // Load dependent data based on grade
                const gradeOption = this.elements.gradeSelect.selectedOptions[0];
                if (gradeOption) {
                    const gradeLevel = gradeOption.dataset.level;
                    const gradeCtgKey = gradeOption.dataset.ctgKey;

                    // Load semesters if elementary grade
                    if (gradeLevel === 'elementray_grade') {
                        await this.loadSemestersForSelect(this.config.existingData.gradeId);
                        if (this.config.existingData.semesterId) {
                            this.elements.semesterSelect.value = this.config.existingData.semesterId;
                        }
                    }
                    // Show Tawjihi first year options
                    else if (programCtgKey === 'tawjihi-and-secondary-program' && gradeCtgKey === 'first_year') {
                        this.showTawjihiFirstYearOptions();
                    }
                    // Load fields if Tawjihi last year
                    else if (programCtgKey === 'tawjihi-and-secondary-program' && gradeCtgKey === 'final_year') {
                        await this.loadFieldsForSelection();
                    }
                }
            }
        }

        // Set flag to allow normal event handling after initial load
        this.isInitialLoad = false;
    }

    /**
     * Attach all event listeners
     */
    attachEventListeners() {
        // Program change event
        this.elements.programSelect.addEventListener('change', () => {
            this.handleProgramChange();
        });

        // Grade change event
        this.elements.gradeSelect.addEventListener('change', () => {
            this.handleGradeChange();
        });
    }

    /**
     * Handle program selection change
     */
    async handleProgramChange() {
        const selectedOption = this.elements.programSelect.selectedOptions[0];

        if (!selectedOption || !selectedOption.value) {
            this.hideAllSections();
            return;
        }

        const programCtgKey = selectedOption.dataset.ctgKey;

        // Reset dependent fields
        this.resetGradeSection();
        this.resetSemesterSection();
        this.hideSpecialSections();

        // Check if grades are needed
        if (this.isProgramWithoutGrades(programCtgKey)) {
            // Hide grade section for programs without grades
            this.elements.gradeSection.style.display = 'none';
            this.elements.gradeRequired.style.display = 'none';
        } else {
            // Show grade section and make it required
            this.elements.gradeSection.style.display = 'block';
            this.elements.gradeRequired.style.display = 'inline';
            this.elements.gradeSelect.setAttribute('required', 'required');

            // Load grades if needed
            await this.loadGrades(selectedOption.value);
        }
    }

    /**
     * Handle grade selection change
     */
    async handleGradeChange() {
        const selectedGrade = this.elements.gradeSelect.selectedOptions[0];

        if (!selectedGrade || !selectedGrade.value) {
            this.resetSemesterSection();
            this.hideSpecialSections();
            return;
        }

        const gradeCtgKey = selectedGrade.dataset.ctgKey;
        const gradeLevel = selectedGrade.dataset.level;
        const programOption = this.elements.programSelect.selectedOptions[0];
        const programCtgKey = programOption ? programOption.dataset.ctgKey : '';

        // Reset sections
        this.resetSemesterSection();
        this.hideSpecialSections();

        // Check for elementary grade with semesters
        if (gradeLevel === 'elementray_grade') {
            await this.loadSemestersForSelect(selectedGrade.value);
        }
        // Check for Tawjihi first year
        else if (programCtgKey === 'tawjihi-and-secondary-program' && gradeCtgKey === 'first_year') {
            this.showTawjihiFirstYearOptions();
        }
        // Check for Tawjihi last year
        else if (programCtgKey === 'tawjihi-and-secondary-program' && gradeCtgKey === 'final_year') {
            await this.loadFieldsForSelection();
        }
        // First year or other grades - no additional selection needed
    }

    /**
     * Check if program doesn't need grades
     */
    isProgramWithoutGrades(ctgKey) {
        return !['tawjihi-and-secondary-program', 'elementary-grades-program'].includes(ctgKey);
    }

    /**
     * Load grades from server
     */
    async loadGrades(programId) {
        try {
            const response = await this.fetchData(this.config.routes.getGrades, {
                program_id: programId
            });

            if (response.no_grades) {
                this.elements.gradeSection.style.display = 'none';
                return;
            }

            this.data.grades = response;
            this.populateGradeSelect(response);
        } catch (error) {
            console.error('Error loading grades:', error);
            this.showError('Failed to load grades');
        }
    }

    /**
     * Load semesters for select dropdown
     */
    async loadSemestersForSelect(gradeId) {
        try {
            const response = await this.fetchData(this.config.routes.getSemesters, {
                grade_id: gradeId
            });

            this.data.semesters = response;
            this.populateSemesterSelect(response);
            this.elements.semesterSection.style.display = 'block';
            this.elements.semesterSelect.setAttribute('required', 'required');
        } catch (error) {
            console.error('Error loading semesters:', error);
            this.showError('Failed to load semesters');
        }
    }

    /**
     * Populate semester select options
     */
    populateSemesterSelect(semesters) {
        // Keep the first option (placeholder)
        const placeholder = this.elements.semesterSelect.querySelector('option[value=""]');
        this.elements.semesterSelect.innerHTML = '';
        if (placeholder) {
            this.elements.semesterSelect.appendChild(placeholder);
        }

        semesters.forEach(semester => {
            const option = document.createElement('option');
            option.value = semester.id;
            option.textContent = this.getLocalizedName(semester);
            this.elements.semesterSelect.appendChild(option);
        });

        // In edit mode, restore selected value if exists
        if (this.config.isEditMode && this.config.existingData.semesterId) {
            this.elements.semesterSelect.value = this.config.existingData.semesterId;
        }
    }

    /**
     * Show Tawjihi first year options
     */
    showTawjihiFirstYearOptions() {
        if (this.elements.tawjihiFirstYearSection) {
            this.elements.tawjihiFirstYearSection.style.display = 'block';

            // Make fields required
            const optionalSelect = document.getElementById('is_optional_single');
            const ministrySelect = document.getElementById('is_ministry_single');

            if (optionalSelect) optionalSelect.setAttribute('required', 'required');
            if (ministrySelect) ministrySelect.setAttribute('required', 'required');
        }
    }

    /**
     * Load fields for Tawjihi last year
     */
    async loadFieldsForSelection() {
        try {
            // Prepare request data
            const requestData = {};

            // If in edit mode, send subject_id to get existing data
            if (this.config.isEditMode && this.config.existingData.subjectId) {
                requestData.subject_id = this.config.existingData.subjectId;
            }

            const response = await this.fetchData(this.config.routes.getFields, requestData);
            this.data.fields = response;
            this.displayFieldsTable(response);
            this.elements.fieldsSelectionSection.style.display = 'block';
        } catch (error) {
            console.error('Error loading fields:', error);
            this.showError('Failed to load fields');
        }
    }

    /**
     * Display fields table for Tawjihi
     */
    displayFieldsTable(fields) {
        this.elements.fieldsTableBody.innerHTML = '';

        fields.forEach(field => {
            const row = document.createElement('tr');

            // Field name cell
            const nameCell = document.createElement('td');
            nameCell.textContent = this.getLocalizedName(field);
            row.appendChild(nameCell);

            // Add to field checkbox cell
            const addCell = document.createElement('td');
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'form-check-input field-checkbox';
            checkbox.name = 'category_id[]';
            checkbox.value = field.id;
            checkbox.id = `field_${field.id}`;

            // Check if field was previously selected
            if (field.checked) {
                checkbox.checked = true;
            }

            const checkboxWrapper = document.createElement('div');
            checkboxWrapper.className = 'form-check';
            checkboxWrapper.appendChild(checkbox);
            addCell.appendChild(checkboxWrapper);
            row.appendChild(addCell);

            // Is Optional select cell
            const optionalCell = document.createElement('td');
            const optionalSelect = this.createYesNoSelect(
                `is_optional[${field.id}]`,
                `is_optional_${field.id}`,
                field.is_optional || false
            );
            optionalSelect.disabled = !field.checked;
            optionalCell.appendChild(optionalSelect);
            row.appendChild(optionalCell);

            // Is Ministry select cell
            const ministryCell = document.createElement('td');
            const ministrySelect = this.createYesNoSelect(
                `is_ministry[${field.id}]`,
                `is_ministry_${field.id}`,
                field.is_ministry !== undefined ? field.is_ministry : true
            );
            ministrySelect.disabled = !field.checked;
            ministryCell.appendChild(ministrySelect);
            row.appendChild(ministryCell);

            // Enable/disable selects based on checkbox
            checkbox.addEventListener('change', (e) => {
                optionalSelect.disabled = !e.target.checked;
                ministrySelect.disabled = !e.target.checked;
            });

            this.elements.fieldsTableBody.appendChild(row);
        });
    }

    /**
     * Create a checkbox element
     */
    createCheckbox(name, value, id, className = '') {
        const wrapper = document.createElement('div');
        wrapper.className = 'form-check';

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.className = `form-check-input ${className}`.trim();
        checkbox.name = name;
        checkbox.value = value;
        checkbox.id = id;

        wrapper.appendChild(checkbox);
        return wrapper;
    }

    /**
     * Create a Yes/No select element
     */
    createYesNoSelect(name, id, defaultValue = false) {
        const select = document.createElement('select');
        select.className = 'form-control form-control-sm';
        select.name = name;
        select.id = id;

        const optionNo = document.createElement('option');
        optionNo.value = '0';
        optionNo.textContent = this.config.translations.no;
        optionNo.selected = !defaultValue;

        const optionYes = document.createElement('option');
        optionYes.value = '1';
        optionYes.textContent = this.config.translations.yes;
        optionYes.selected = defaultValue;

        select.appendChild(optionNo);
        select.appendChild(optionYes);

        return select;
    }

    /**
     * Populate grade select options
     */
    populateGradeSelect(grades) {
        // Keep the first option (placeholder)
        const placeholder = this.elements.gradeSelect.querySelector('option[value=""]');
        this.elements.gradeSelect.innerHTML = '';
        if (placeholder) {
            this.elements.gradeSelect.appendChild(placeholder);
        }

        grades.forEach(grade => {
            const option = document.createElement('option');
            option.value = grade.id;
            option.textContent = this.getLocalizedName(grade);
            option.dataset.ctgKey = grade.ctg_key || '';
            option.dataset.level = grade.level || '';
            this.elements.gradeSelect.appendChild(option);
        });
    }

    /**
     * Get localized name based on current locale
     */
    getLocalizedName(item) {
        const locale = document.documentElement.lang || 'ar';
        return locale === 'ar' ? item.name_ar : (item.name_en || item.name_ar);
    }

    /**
     * Reset grade section
     */
    resetGradeSection() {
        this.elements.gradeSelect.value = '';
        this.elements.gradeSelect.removeAttribute('required');
    }

    /**
     * Reset semester section
     */
    resetSemesterSection() {
        this.elements.semesterSelect.value = '';
        this.elements.semesterSelect.removeAttribute('required');
        this.elements.semesterSection.style.display = 'none';

        // Clear semester options except placeholder
        const placeholder = this.elements.semesterSelect.querySelector('option[value=""]');
        this.elements.semesterSelect.innerHTML = '';
        if (placeholder) {
            this.elements.semesterSelect.appendChild(placeholder);
        }
    }

    /**
     * Hide special sections (fields table and Tawjihi first year options)
     */
    hideSpecialSections() {
        this.elements.fieldsSelectionSection.style.display = 'none';
        this.elements.fieldsTableBody.innerHTML = '';

        if (this.elements.tawjihiFirstYearSection) {
            this.elements.tawjihiFirstYearSection.style.display = 'none';

            // Remove required from fields
            const optionalSelect = document.getElementById('is_optional_single');
            const ministrySelect = document.getElementById('is_ministry_single');

            if (optionalSelect) optionalSelect.removeAttribute('required');
            if (ministrySelect) ministrySelect.removeAttribute('required');
        }
    }

    /**
     * Hide all sections
     */
    hideAllSections() {
        this.elements.gradeSection.style.display = 'none';
        this.resetSemesterSection();
        this.hideSpecialSections();
    }

    /**
     * Fetch data from server
     */
    async fetchData(url, data = {}) {
        const formData = new FormData();
        formData.append('_token', this.csrfToken);
        if(this.config.existingData) formData.append('subject_id', this.config.existingData.subjectId);

        for (const key in data) {
            formData.append(key, data[key]);
        }

        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': this.csrfToken
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return await response.json();
    }

    /**
     * Show error message
     */
    showError(message) {
        // You can customize this to show errors in a better way
        console.error(message);
        alert(message);
    }
}
