import "./app.sass";

import React, { useEffect } from "react";
import { TextField } from '@fluentui/react/lib/TextField';
import { Stack, IStackProps, IStackStyles } from '@fluentui/react/lib/Stack';
import { Text } from '@fluentui/react/lib/Text';
import { MessageBar } from "@fluentui/react/lib/MessageBar";
import { ChoiceGroup, IChoiceGroupOption } from '@fluentui/react/lib/ChoiceGroup';
import { Dropdown, IDropdownStyles } from '@fluentui/react/lib/Dropdown';
import { Checkbox, initializeIcons, PrimaryButton } from '@fluentui/react';

const App :React.FC = ()=>{

  initializeIcons();

  const stackTokens = { childrenGap: 50 };
  const dropdownStyles: Partial<IDropdownStyles> = { dropdown: { width: "100%" }, root: { height: 100 } };
  const stackStyles: Partial<IStackStyles> = { root: { minWidth: "90%" , margin: 'auto', marginTop : '3rem', display : 'flex', flexDirection: 'row', justifyContent: 'center'} };
  const columnProps: Partial<IStackProps> = {
    tokens: { childrenGap: 15 },
    styles: { root: { width: '50%', minWidth: 350} },
  };

  const options: IChoiceGroupOption[] = [
    { key: 'M', text: 'Masculino' },
    { key: 'F', text: 'Femenino' },
  ];

  const DropdownErrorExampleOptions = [
    { key: 'A', text: 'Option a' },
    { key: 'B', text: 'Option b' },
    { key: 'C', text: 'Option c' },
    { key: 'D', text: 'Option d' },
    { key: 'E', text: 'Option e' },
  ];

  const ListRoles = [
    { key: '1', text: 'Role 1' },
    { key: '2', text: 'Role 2' },
    { key: '3', text: 'Role 3' },
    { key: '4', text: 'Role 4' },
  ]

  function _alertClicked(): void {
    alert('Clicked');
  }

  useEffect(() => {
    
  });

  return(
    
      <React.Fragment>
          <div className="wrap">
              <div className="container">

                <Text block nowrap variant="mega" style={{textAlign: 'center'}}>Crear Empleado.</Text>

                <Stack tokens={stackTokens} style={{marginTop: '3rem'}}>

                  <MessageBar>
                    Los campos con (*) son obligatorios.
                  </MessageBar>

                </Stack>

                <Stack horizontal tokens={stackTokens} styles={stackStyles}>

                  <Stack {...columnProps}>
                    <TextField label="Nombre Completo" required/>
                    <ChoiceGroup defaultSelectedKey="B" options={options} label="Sexo" required={true} />
                    <TextField label="Descripción" multiline rows={3} required/>
                  </Stack>

                  <Stack {...columnProps}>
                    <TextField label="Correo electrónico" required/>
                    <Dropdown
                      placeholder="Select an option"
                      label="Area"
                      options={DropdownErrorExampleOptions}
                      styles={dropdownStyles}
                      required
                    />
                    <Checkbox label="Deseo recibir boletín informativo."/>

                    <Dropdown
                      placeholder="Select an option"
                      label="Role"
                      options={ListRoles}
                      styles={dropdownStyles}
                      multiSelect
                      required
                    />

                  </Stack>

                </Stack>

                <PrimaryButton text="Primary" onClick={_alertClicked} allowDisabledFocus />

              </div>
          </div>
      </React.Fragment>
  )
}

export {App}