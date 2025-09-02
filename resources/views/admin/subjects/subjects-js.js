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
            fieldsSelectionSection: document.getElementById('fieldsSelectionSection'),
            fieldsTableBody: document.getElementById('fieldsTableBody')
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
    init() {
        this.attachEventListeners();
        // Check if there's an initial program selected (for edit mode)
        if (this.elements.programSelect.value) {
            this.handleProgramChange();
        }
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
     * Load fields for Tawjihi last year
     */
    async loadFieldsForSelection() {
        try {
            const response = await this.fetchData(this.config.routes.getFields);
            console.log(response);
            this.data.fields = response;
            this.displayFieldsTable(response);
            this.elements.fieldsSelectionSection.style.display = 'block';
        } catch (error) {
            console.error('Error loading fields:', error);
            this.showError('Failed to load fields');
        }
    }

    /**
     * Display semesters as checkboxes
     */
    displaySemestersCheckboxes(semesters) {
        this.elements.semestersCheckboxes.innerHTML = '';

        semesters.forEach(semester => {
            const col = document.createElement('div');
            col.className = 'col-md-4 col-sm-6';

            const formCheck = document.createElement('div');
            formCheck.className = 'form-check mb-2';

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'form-check-input';
            checkbox.id = `semester_${semester.id}`;
            checkbox.name = 'semester_categories[]';
            checkbox.value = semester.id;

            const label = document.createElement('label');
            label.className = 'form-check-label';
            label.htmlFor = `semester_${semester.id}`;
            label.textContent = this.getLocalizedName(semester);

            formCheck.appendChild(checkbox);
            formCheck.appendChild(label);
            col.appendChild(formCheck);
            this.elements.semestersCheckboxes.appendChild(col);
        });
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
            const addCheckbox = this.createCheckbox(
                `category_id[]`,
                field.id,
                `field_${field.id}`,
                'field-checkbox'
            );
            addCell.appendChild(addCheckbox);
            row.appendChild(addCell);

            // Is Optional select cell
            const optionalCell = document.createElement('td');
            const optionalSelect = this.createYesNoSelect(
                `is_optional[${field.id}]`,
                `is_optional_${field.id}`,
                false
            );
            optionalSelect.disabled = true; // Initially disabled
            optionalCell.appendChild(optionalSelect);
            row.appendChild(optionalCell);

            // Is Ministry select cell
            const ministryCell = document.createElement('td');
            const ministrySelect = this.createYesNoSelect(
                `is_ministry[${field.id}]`,
                `is_ministry_${field.id}`,
                true
            );
            ministrySelect.disabled = true; // Initially disabled
            ministryCell.appendChild(ministrySelect);
            row.appendChild(ministryCell);

            // Enable/disable selects based on checkbox
            addCheckbox.addEventListener('change', (e) => {
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
     * Hide special sections (fields table only)
     */
    hideSpecialSections() {
        this.elements.fieldsSelectionSection.style.display = 'none';
        this.elements.fieldsTableBody.innerHTML = '';
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
