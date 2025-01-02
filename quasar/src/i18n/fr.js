export default {
  error: {
    incorrectPassword: 'Mot de passe incorrect',
    invalidCredentials: 'Identifiants invalides',
    incorrectCurrentPassword: 'Mot de passe actuel incorrect',
    theLinkHasExpired: 'Le lien a expiré',
    invalidLink: 'Lien invalide',
    phoneNumberAlreadyUsed: 'Numéro de téléphone déjà utilisé',
    phoneNumberInvalid: 'Numéro de téléphone invalide',
    pleaseChooseAFutureDate: 'Veuillez choisir une date future',
    pleaseChooseAPastDate: 'Veuillez choisir une date passée',
    passwordsDoNotMatch: 'Les mots de passe ne correspondent pas',
    somethingWentWrong: 'Une erreur est survenue',
    passwordComplexity: 'Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial',
    emailAlreadyUsed: 'Adresse e-mail déjà utilisée',
    emailInvalid: 'Adresse e-mail invalide',
    notFound: 'Ressource introuvable',
    teamCannotBeDeleted: 'Cette équipe ne peut pas être supprimée',
    chooseSubcriptionForTeam: 'Vous devez choisir un abonnement pour créer une équipe',
    incorrectReccurence: 'Récurrence invalide',
    alreadyChosenSubscription: 'Vous avez déjà choisi cet abonnement',
    aleardyPlansChangeSchedule: 'Vous avez déjà initié un changement de plan, veuillez attendre que le abonnement soit effectif'
  },
  breadcrumb: {
    home: 'Accueil',
    createNewProject: 'Créer un nouveau projet'
  },
  nav: {
    logout: 'Se déconnecter',
    profile: 'Profil',
    settings: 'Paramètres',
    minimize: 'Réduire',
    changePlan: "Changer d'offre",
    logoutSuccess: 'Vous avez bien été déconnecté',
    lang: 'Sélectionner la langue'
  },
  form: {
    error: {
      required: 'Ce champ est obligatoire',
      email: 'Veuillez saisir une adresse e-mail valide',
      min: 'Ce champ doit contenir au moins {min} caractères',
      max: 'Ce champ doit contenir au plus {max} caractères'
    },
    user: {
      label: {
        firstName: 'Prénom',
        lastName: 'Nom',
        email: 'Adresse e-mail',
        password: 'Mot de passe',
        confirmPassword: 'Confirmation du mot de passe',
        currentPassword: 'Mot de passe actuel',
        newPassword: 'Nouveau mot de passe',
        confirmNewPassword: 'Confirmation du nouveau mot de passe',
        phoneNumber: 'Numéro de téléphone'
      },
      placeholder: {
        firstName: 'John',
        lastName: 'Doe',
        email: 'john.doe@gmail.com',
        password: 'Votre mot de passe',
        confirmPassword: 'Encore une fois',
        currentPassword: 'Votre mot de passe actuel',
        newPassword: 'Votre nouveau mot de passe',
        confirmNewPassword: 'Encore une fois'
      }
    },
    project: {
      label: {
        name: 'Nom du projet',
        description: 'Description',
        type: 'Type de projet'
      },
      placeholder: {
        name: 'Ajouter un nom à votre projet',
        description: 'Décrivez au maximum votre projet'
      }
    },
    team: {
      label: {
        name: "Nom de l'équipe"
      },
      placeholder: {
        name: 'Ajouter un nom à votre équipe'
      }
    }
  },
  notFound: {
    title: 'Page introuvable',
    content: "La page que vous cherchez n'existe pas.",
    backToHome: "Revenir à la page d'accueil"
  },
  forgotPassword: {
    title: 'Mot de passe oublié',
    content: "Veuillez saisir votre adresse e-mail pour réinitialiser votre mot de passe. Si l'adresse e-mail est reliée a un compte existant, un e-mail vous sera envoyé avec un lien pour réinitialiser votre mot de passe.",
    submit: 'Envoyer',
    back: 'Retour à la page de connexion',
    success: "Vous recevrez prochainement un mail de réinitialisation de mot de passe si l'adresse {email} est reliée à un compte existant."
  },
  resetPassword: {
    title: 'Réinitialiser votre mot de passe',
    content: 'Veuillez saisir votre nouveau mot de passe.',
    submit: 'Confirmer',
    back: 'Retour à la page de connexion',
    success: 'Votre mot de passe a bien été réinitialisé.'
  },
  signup: {
    title: 'Inscription',
    content: 'Veuillez saisir vos informations pour vous inscrire.',
    submit: "S'inscrire",
    alreadySigned: 'Déjà inscrit ?',
    login: 'Se connecter',
    success: 'Inscription réussie',
    passwordHint: '8 caractères minimum, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial',
    successCard: {
      title: 'Inscription réussie',
      content: 'Un mail de confirmation a été envoyé à votre adresse email. Veuillez vérifier votre boîte de réception ainsi que vos spams.',
      action: 'Retour à la page de connexion'
    }
  },
  signin: {
    title: 'Connexion',
    content: 'Veuillez saisir vos identifiants pour vous connecter.',
    submit: 'Se connecter',
    forgotPassword: 'Mot de passe oublié ?',
    resetPassword: 'Réinitialiser votre mot de passe',
    noAccount: 'Pas encore de compte ?',
    signup: "S'inscrire",
    success: 'Vous avez bien été connecté !'
  },
  emailConfirmation: {
    successTitle: 'Votre email a bien été confirmée !',
    action: 'Retour à la page de connexion'
  },
  home: {
    createNewTeam: 'Créer une nouvelle équipe',
    organizeYourProjects: 'Organisez vos projets',
    teams: 'Équipes',
    projects: 'Projets'
  },
  team: {
    createModal: {
      title: 'Créer une nouvelle équipe',
      submit: 'Créer',
      success: 'Équipe créée avec succès'
    },
    editModal: {
      title: 'Modifier l\'équipe "{name}"',
      submit: 'Sauvegarder',
      success: 'Équipe sauvegardée avec succès',
      error403: "Seul le manager de l'équipe peut la modifier"
    },
    details: {
      projects: 'Projets',
      noProject: 'Aucun projet disponible',
      deleteBtn: 'Supprimer',
      changePlan: "Changer d'offre",
      subscribeSuccess1: 'Abonnement effectué avec succès',
      subscribeSuccess2: 'Merci de votre confiance !',
      closeSuccess: 'Fermer'
    },
    list: {
      noTeam: 'Aucune équipe disponible',
      newTeam: 'Créer une nouvelle équipe'
    },
    card: {
      edited: 'Modifiée',
      project: 'projet',
      projects: 'projets'
    },
    deleteModal: {
      title: 'Suppression de l\'équipe "{name}"',
      content: 'Êtes-vous sûr de vouloir supprimer l\'équipe "{name}" ?<br/>Tous les projets associés seront également supprimés.<br/>Cette action est irréversible.',
      submit: 'Supprimer',
      success: 'Équipe supprimée avec succès',
      error: "Une erreur est survenue lors de la suppression de l'équipe",
      error403: "Seul le manager de l'équipe peut la supprimer"
    },
    errorNotFound: "L'équipe est introuvable"
  },
  project: {
    create: {
      step1: 'Créez votre projet',
      step2: 'Définissez votre projet',
      step3: 'Choisissez les documents à générer',
      nextStep: "Passer à l'étape suivante",
      submit: 'Générer',
      back: 'Retour',
      success: 'Projet créé avec succès',
      generateDocumentsError: 'Une erreur est survenue lors de la génération des documents',
      createProjectError: 'Une erreur est survenue lors de la création du projet'
    },
    sellingObject: {
      product_for_sale: 'Produit à vendre',
      service: 'Service/Prestation',
      concept: 'Produit innovant/Concept'
    },
    card: {
      edited: 'Modifié'
    },
    list: {
      noProject: 'Aucun projet disponible',
      newProject: 'Nouveau projet'
    },
    editModal: {
      title: 'Modifier votre projet',
      submit: 'Enregistrer',
      success: 'Projet mis à jour avec succès'
    },
    regenerateModal: {
      title: 'Re-généner votre projet',
      submit: 'Re-générer',
      success: 'Projet mis à jour avec succès',
      documentsLabel: 'Documents à re-générer',
      generateDocumentsError: 'Une erreur est survenue lors de la génération des documents',
      updateProjectError: 'Une erreur est survenue lors de la mise à jour du projet'
    },
    deleteModal: {
      title: 'Suppression du projet "{name}"',
      content: 'Êtes-vous sûr de vouloir supprimer le projet "{name}" ? Cette action est irréversible.',
      submit: 'Supprimer',
      success: 'Projet supprimé avec succès',
      error: 'Une erreur est survenue lors de la suppression du projet'
    },
    details: {
      voidProjectTitle: 'Votre projet est vide',
      voidProjectText: 'Ajoutez des informations pour commencer à travailler sur votre projet',
      generate: 'Générer la stratégie',
      editBtn: 'Modifier',
      regenerateBtn: 'Re-générer',
      deleteBtn: 'Supprimer'
    },
    errorNotFound: 'Projet introuvable'
  },
  document: {
    errorNotFound: 'Document introuvable',
    defaultError: 'Impossible de charger le document',
    name: {
      business_model_canvas: 'Business Model Canvas',
      buyer_persona: 'Buyer Persona',
      competitor_analysis: 'Analyse de la concurence',
      golden_triangle: "Triange d'or",
      marketing_mix4: '4P',
      marketing_mix5: '5P',
      pestel: 'PESTEL',
      smart: 'SMART',
      stp: 'STP',
      swot: 'SWOT'
    },
    list: {
      noDocument: 'Aucun document disponible',
      newDocument: 'Générer un document'
    }
  },
  subscription: {
    card: {
      choice: 'Choisir',
      monthlyPrice: 'Prix : {price} € /mois',
      yearlyPrice: 'Soit {price} € /an',
      current: 'Actuel'
    },
    choicePlanModal: {
      title: "Modifier l'offre de votre équipe",
      loadPlansError: 'Une erreur est survenue lors du chargement des offres',
      confirm: 'Confirmer',
      createStripeSessionError: 'Une erreur est survenue lors de la création de la session de paiement',
      monthly: 'Mensuel',
      yearly: 'Annuel',
      changePlanWarning: "Le changement d'offre sera effectif à la fin de votre période actuelle",
      updateSuccess: 'Formule mise à jour avec succès',
      changePlanAlreadyScheduledWarning: 'Vous avez déjà initié un changement de plan, veuillez attendre que le abonnement soit effectif'
    }
  },
  profile: {
    title: 'Mon profil',
    resetPassword: {
      title: 'Réinitialisation de votre mot de passe',
      content: 'Veuillez saisir votre nouveau mot de passe.'
    }
  }
}
