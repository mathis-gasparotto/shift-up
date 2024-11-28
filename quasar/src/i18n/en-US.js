export default {
  error: {
    incorrectPassword: 'Incorrect password',
    invalidCredentials: 'Invalid credentials',
    incorrectCurrentPassword: 'Incorrect current password',
    theLinkHasExpired: 'The link has expired',
    invalidLink: 'Invalid link',
    phoneNumberAlreadyUsed: 'Phone number already used',
    phoneNumberInvalid: 'Invalid phone number',
    pleaseChooseAFutureDate: 'Please choose a future date',
    pleaseChooseAPastDate: 'Please choose a past date',
    passwordsDoNotMatch: 'Passwords do not match',
    somethingWentWrong: 'Something went wrong',
    passwordComplexity: 'Password must contain at least 8 characters, an uppercase letter, a lowercase letter, a number and a special character',
    emailAlreadyUsed: 'Email address already used',
    emailInvalid: 'Invalid email address',
    notFound: 'Resource not found',
    teamCannotBeDeleted: 'This team cannot be deleted',
    chooseSubcriptionForTeam: 'You have to choose a subscription plan to create a team'
  },
  breadcrumb: {
    home: 'Home',
    createNewProject: 'Create a new project'
  },
  nav: {
    logout: 'Logout',
    profile: 'Profile',
    settings: 'Settings',
    minimize: 'Minimize',
    changePlan: 'Change plan',
    logoutSuccess: 'You have been successfully logged out',
    lang: 'Select language'
  },
  form: {
    error: {
      required: 'This field is required',
      email: 'Please enter a valid email address',
      min: 'This field must contain at least {min} characters',
      max: 'This field must contain at most {max} characters'
    },
    user: {
      label: {
        firstName: 'First name',
        lastName: 'Last name',
        email: 'Email address',
        password: 'Password',
        confirmPassword: 'Confirm password',
        currentPassword: 'Current password',
        newPassword: 'New password',
        confirmNewPassword: 'Confirm new password',
        phoneNumber: 'Phone number'
      },
      placeholder: {
        firstName: 'John',
        lastName: 'Doe',
        email: 'john.doe@gmail.com',
        password: 'Your password',
        confirmPassword: 'Once again',
        currentPassword: 'Your current password',
        newPassword: 'Your new password',
        confirmNewPassword: 'Once again'
      }
    },
    project: {
      label: {
        name: 'Project name',
        description: 'Description',
        type: 'Project type'
      },
      placeholder: {
        name: 'Add a name to your project',
        description: 'Describe your project as much as possible'
      }
    },
    team: {
      label: {
        name: 'Team name'
      },
      placeholder: {
        name: 'Add a name to your team'
      }
    }
  },
  notFound: {
    title: 'Page not found',
    content: 'The page you are looking for does not exist.',
    backToHome: 'Back to home'
  },
  forgotPassword: {
    title: 'Forgot password',
    content: 'Please enter your email address to reset your password. If the email address is linked to an existing account, an email will be sent to you with a link to reset your password.',
    submit: 'Send',
    back: 'Back to login page',
    success: 'You will soon receive a password reset email if the address {email} is linked to an existing account.'
  },
  resetPassword: {
    title: 'Reset your password',
    content: 'Please enter your new password.',
    submit: 'Confirm',
    back: 'Back to login page',
    success: 'Your password has been successfully reset.'
  },
  signup: {
    title: 'Sign up',
    content: 'Please enter your information to sign up.',
    submit: 'Sign up',
    alreadySigned: 'Already signed up?',
    login: 'Log in',
    success: 'Sign up successful',
    passwordHint: '8 characters minimum, an uppercase letter, a lowercase letter, a number and a special character',
    successCard: {
      title: 'Sign up successful',
      content: 'A confirmation email has been sent to your email address. Please check your inbox as well as your spam folder.',
      action: 'Back to login page'
    }
  },
  signin: {
    title: 'Log in',
    content: 'Please enter your credentials to log in.',
    submit: 'Log in',
    forgotPassword: 'Forgot password?',
    resetPassword: 'Reset your password',
    noAccount: 'No account yet?',
    signup: 'Sign up',
    success: 'You have been successfully logged in!'
  },
  emailConfirmation: {
    successTitle: 'Your email has been successfully confirmed!',
    action: 'Back to login page'
  },
  home: {
    createNewTeam: 'Create a new team',
    organizeYourProjects: 'Organize your projects',
    teams: 'Teams',
    projects: 'Projects'
  },
  team: {
    choicePlanModal: {
      title: 'Modify your team subscription plan',
      loadPlansError: 'An error occurred while loading the plans',
      confirm: 'Confirm',
      createStripeSessionError: 'An error occurred while creating the payment session'
    },
    createModal: {
      title: 'Create a new team',
      submit: 'Create',
      success: 'Team created successfully'
    },
    editModal: {
      title: 'Edit team "{name}"',
      submit: 'Save',
      success: 'Team saved successfully',
      error403: 'Only the team manager can edit it'
    },
    details: {
      projects: 'Projects',
      noProject: 'No project available',
      deleteBtn: 'Delete',
      changePlan: 'Change plan',
      subscribeSuccess1: 'Subscription successful',
      subscribeSuccess2: 'Thank you for your trust!',
      closeSuccess: 'Close'
    },
    list: {
      noTeam: 'No team available',
      newTeam: 'Create a new team'
    },
    card: {
      edited: 'Edited',
      project: 'project',
      projects: 'projects'
    },
    deleteModal: {
      title: 'Delete team "{name}"',
      content: 'Are you sure you want to delete the "{name}" team?<br/>All associated projects will also be deleted.<br/>This action cannot be undone.',
      submit: 'Delete',
      success: 'Team deleted successfully',
      error: 'An error occurred while deleting the team',
      error403: 'Only the team manager can delete the team'
    },
    errorNotFound: 'Team not found'
  },
  project: {
    create: {
      step1: 'Create your project',
      step2: 'Define your project',
      step3: 'Choose the documents to generate',
      nextStep: 'Go to the next step',
      submit: 'Generate',
      back: 'Back',
      success: 'Project created successfully',
      generateDocumentsError: 'An error occurred while generating the documents',
      createProjectError: 'An error occurred while creating the project'
    },
    sellingObject: {
      product_for_sale: 'Product for sale',
      service: 'Service',
      concept: 'Innovative product/Concept'
    },
    card: {
      edited: 'Edited'
    },
    list: {
      noProject: 'No project available',
      newProject: 'New project'
    },
    editModal: {
      title: 'Edit your project',
      submit: 'Save',
      success: 'Project updated successfully'
    },
    regenerateModal: {
      title: 'Re-generate your project',
      submit: 'Re-generate',
      success: 'Project updated successfully',
      documentsLabel: 'Documents to re-generate',
      generateDocumentsError: 'An error occurred while generating the documents',
      updateProjectError: 'An error occurred while updating the project'
    },
    deleteModal: {
      title: 'Delete project "{name}"',
      content: 'Are you sure you want to delete the "{name}" project? This action cannot be undone.',
      submit: 'Delete',
      success: 'Project deleted successfully',
      error: 'An error occurred while deleting the project'
    },
    details: {
      voidProjectTitle: 'Your project is empty',
      voidProjectText: 'Add information to start working on your project',
      generate: 'Generate strategy',
      editBtn: 'Edit',
      regenerateBtn: 'Re-generate',
      deleteBtn: 'Delete'
    },
    errorNotFound: 'Project not found'
  },
  document: {
    errorNotFound: 'Document not found',
    defaultError: 'Unable to load the document',
    name: {
      business_model_canvas: 'Business Model Canvas',
      buyer_persona: 'Buyer Persona',
      competitor_analysis: 'Competitor analysis',
      golden_triangle: 'Golden triangle',
      marketing_mix4: '4P',
      marketing_mix5: '5P',
      pestel: 'PESTEL',
      smart: 'SMART',
      stp: 'STP',
      swot: 'SWOT'
    },
    list: {
      noDocument: 'No document available',
      newDocument: 'Generate document'
    }
  },
  subscription: {
    card: {
      choice: 'Choose'
    }
  }
}
