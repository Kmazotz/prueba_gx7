import "./app.sass";

import React, { useEffect } from "react";
import { TextField } from '@fluentui/react/lib/TextField';
import { Stack, IStackProps, IStackStyles } from '@fluentui/react/lib/Stack';
import { Text } from '@fluentui/react/lib/Text';
import { MessageBar, MessageBarType } from "@fluentui/react/lib/MessageBar";
import { ChoiceGroup, IChoiceGroupOption } from '@fluentui/react/lib/ChoiceGroup';
import { Dropdown, IDropdownOption, IDropdownStyles } from '@fluentui/react/lib/Dropdown';
import { Checkbox, CommandBarButton, DefaultButton, DetailsList, FocusTrapCallout, FocusZone, FocusZoneTabbableElements, FontWeights, IColumn, IIconProps, initializeIcons, mergeStyleSets, Panel, PrimaryButton, Selection, SelectionMode } from '@fluentui/react';
import { useBoolean, useId} from '@fluentui/react-hooks';

export interface Empleado{
  id: number,
  name: string,
  email: string,
  sex: string
  description: string
}

const App :React.FC = ()=>{

  initializeIcons();

  const [messageType, setMessageType] = React.useState('warning');
  const [messageContent, setMessageContent] = React.useState('Los campos con (*) son obligatorios.');

//#region Collect form Data
  const [name, setName] = React.useState('');
  const [email, setEmail] = React.useState('');
  const [sexo, setSexo] = React.useState('');
  const [selectedArea, setSelectedArea] = React.useState(0);
  const [selectedItemArea, setSelectedItemArea] = React.useState<IDropdownOption>();
  const [description, setDescription] = React.useState('');
  const [boletin, setBoletin] = React.useState(false);
  const [selectedRoles, setSelectedRoles] = React.useState<string[]>([]);
//#endregion

//#region edit form Data
  const [editId, setEditID] = React.useState(0);
  const [editName, setEditName] = React.useState('');
  const [editEmail, setEditEmail] = React.useState('');
  const [editSexo, setEditSexo] = React.useState('');
  const [editDescription, setEditDescription] = React.useState('');
//#endregion

  const stackTokens = { childrenGap: 50 };
  const dropdownStyles: Partial<IDropdownStyles> = { dropdown: { width: "100%" }, root: { height: 100 } };
  const stackStyles: Partial<IStackStyles> = { root: { minWidth: "90%" , margin: 'auto', marginTop : '3rem', display : 'flex', flexDirection: 'row', justifyContent: 'center'} };
  const stackPanelStyles: Partial<IStackStyles> = { root: { minWidth: 150 , margin: 'auto', marginTop : '3rem', display : 'flex', flexDirection: 'column', justifyContent: 'center'} };
  const columnProps: Partial<IStackProps> = {
    tokens: { childrenGap: 15 },
    styles: { root: { width: '50%', minWidth: 350} },
  };
  const columnPanelProps: Partial<IStackProps> = {
    tokens: { childrenGap: 15 },
    styles: { root: { width: '50%', minWidth: 280} },
  };

  const deleteIcon: IIconProps = { iconName: 'Trash' };
  const updateteIcon: IIconProps = { iconName: 'Refresh' };

  const stackManagementButtonStyles: Partial<IStackStyles> = { root: { height: 44, marginTop: '2rem', background: "#fff"} };

  const options: IChoiceGroupOption[] = [
    { key: 'M', text: 'Masculino' },
    { key: 'F', text: 'Femenino' },
  ];

  let DropDownAreaList = [{ key: NaN, text: 'Seleccione una opción'}];

  let DropDownRoleList = [{ key: NaN, text: 'Seleccione una opción'}];

  const _columns : IColumn[] = [
    { key: 'id', name: 'ID', fieldName: 'id', minWidth: 100, maxWidth: 200, isResizable: true },
    { key: 'nombre', name: 'Nombre', fieldName: 'nombre', minWidth: 100, maxWidth: 200, isResizable: true },
    { key: 'email', name: 'Email', fieldName: 'email', minWidth: 100, maxWidth: 200, isResizable: true },
    { key: 'sexo', name: 'Sexo', fieldName: 'sexo', minWidth: 100, maxWidth: 200, isResizable: true },
    { key: 'descripcion', name: 'Descripcion', fieldName: 'descripcion', minWidth: 100, maxWidth: 200, isResizable: true },
  ];

  const [_items, setItem] = React.useState<Empleado[]>([]);

  const [_selectionDetail, setSelectionDetail] = React.useState<Empleado | undefined>(undefined);
  const _selection = new Selection({
    onSelectionChanged : () => {
      setSelectionDetail(_selection.getSelection()[0] as Empleado);
    }
    
  });

  const [isOpen, { setTrue: openPanel, setFalse: dismissPanel }] = useBoolean(false);
  const [isCalloutVisible, { toggle: toggleIsCalloutVisible }] = useBoolean(false);
  const buttonId = useId('callout-button');
  const styles = mergeStyleSets({
    callout: {
      width: 320,
      padding: '20px 24px',
    },
    title: {
      marginBottom: 12,
      fontWeight: FontWeights.semilight,
    },
    buttons: {
      display: 'flex',
      justifyContent: 'flex-end',
      marginTop: 20,
    },
  });

  function _getEmpleados(){
    fetch('http://localhost:8000/empleado/list',
    {
      method: "GET",
      headers: {"Content-type": "application/json;charset=UTF-8"}
    }).then(res => {
      if(res.ok){
        res.json().then(response => {
          for (let i = 0; i < response.data.length; i++) {

            const empleado : Empleado = {
              id: response.data[i].id as number,
              name: response.data[i].nombre as string,
              email: response.data[i].email as string,
              sex: response.data[i].sexo as string,
              description : response.data[i].descripcion as string
            }
            
            setItem(_items => _items.filter(key => key.id === empleado.id).length === 0 ? [..._items, empleado as Empleado] : _items.filter(key => key.id !== empleado.id));

          }
        })
      }
    }) 
  };

  (function _getAreas(){

    fetch('http://localhost:8000/area/list',
    {
      method: "GET",
      headers: {"Content-type": "application/json;charset=UTF-8"}
    }).then(res => {
      if(res.ok){
        res.json().then(response => {
          response.data.forEach((element: any) => {
            DropDownAreaList.push({key: element.id, text: element.nombre});
          })
        })
      }
    });
  })();

  (function _getRoles(){

    fetch('http://localhost:8000/role/list',
    {
      method: "GET",
      headers: {"Content-type": "application/json;charset=UTF-8"}
    }).then(res => {
      if(res.ok){
        res.json().then(response => {
          response.data.forEach((element: any) => {
            DropDownRoleList.push({key: element.id, text: element.nombre});
          })
        })
      }
    });

  })();

  const _onChangeName = React.useCallback(
    (event: React.FormEvent<HTMLInputElement | HTMLTextAreaElement>, newValue?: string) => {
      setName(newValue || '');
    },
    [],
  );

  const _onChangeEditName = React.useCallback(
    (event: React.FormEvent<HTMLInputElement | HTMLTextAreaElement>, newValue?: string) => {
      setEditName(newValue || '');
    },
    [],
  );

  const _onChangeEmail = React.useCallback(
    (event: React.FormEvent<HTMLInputElement | HTMLTextAreaElement>, newValue?: string) => {
      setEmail(newValue || '');
    },
    [],
  );

  const _onChangeEditEmail = React.useCallback(
    (event: React.FormEvent<HTMLInputElement | HTMLTextAreaElement>, newValue?: string) => {
      setEditEmail(newValue || '');
    },
    [],
  );

  const _onChangeSexo = React.useCallback(
    (ev?: React.FormEvent<HTMLElement | HTMLInputElement>, option?: IChoiceGroupOption) => {
      if(option){
        setSexo(option.key || '');
      }
    },
    [],
  );

  const _onChangeEditSexo = React.useCallback(
    (ev?: React.FormEvent<HTMLElement | HTMLInputElement>, option?: IChoiceGroupOption) => {
      if(option){
        setEditSexo(option.key || '');
      }
    },
    [],
  );

  const _onChangeArea = React.useCallback(
    (event: React.FormEvent<HTMLDivElement>, option?: IDropdownOption, index?: number) => {
      if(option?.key !== undefined){
        if(typeof option.key === 'number'){
          setSelectedArea(option.key || NaN);
          setSelectedItemArea(option);
        }
      }
    },
    []
  );

  const _onChangeDescription = React.useCallback(
    (event: React.FormEvent<HTMLInputElement | HTMLTextAreaElement>, newValue?: string) => {
      setDescription(newValue || '');
    },
    [],
  );

  const _onChangeEditDescription = React.useCallback(
    (event: React.FormEvent<HTMLInputElement | HTMLTextAreaElement>, newValue?: string) => {
      setEditDescription(newValue || '');
    },
    [],
  );

  const _onChangeBoletin = React.useCallback(
    (ev?: React.FormEvent<HTMLElement | HTMLInputElement>, checked?: boolean) => {
      setBoletin(checked || false);
    },
    []
  );

  const _onChangeRoles = React.useCallback(
    (event: React.FormEvent<HTMLDivElement>, option?: IDropdownOption, index?: number) => {
      if (option) {
        setSelectedRoles( selectedRoles => 
         option.selected ? [...selectedRoles, option.key as string] : selectedRoles.filter(key => key !== option.key)
        );
      }
    },
    []
  );

  function _saveEmpleado(): void {
    
    let dataComplete = false;

    let data = {
      nombre : name,
      email : email,
      sexo : sexo,
      area_id : selectedArea,
      boletin : boletin,
      descripcion: description,
      roles: selectedRoles
    }

    if(data != null){
     
      Object.values(data).map(value => {

        if(value === "" || value.toString().length === 0 || value === 0){
          dataComplete = false;
          setMessageType('error');
          setMessageContent('Los campos con (*) son obligatorios.');
        }else{
          dataComplete = true;
        }

      });

      if(dataComplete){
          fetch('http://localhost:8000/empleado/store',{
            method: 'POST',
            body: JSON.stringify(data),
            headers:{
              'Content-Type': 'application/json'
            }
          }).then(res => res.json())
          .catch(err => {
            setMessageType('error');
            setMessageContent(err);
          })
          .then(response => {
            if(response){
              setMessageType(response.title.toLowerCase());
              setMessageContent(response.message);
            }
          });

        setMessageType('success');
        setMessageContent('Campos completos.');
      }

    }else{
      setMessageType('error');
      setMessageContent('Los campos con (*) son obligatorios.');
    }
  }

  function _updateEmpleado(): void {
    
    let dataComplete = false;

    let data = {
      id: editId,
      nombre : editName,
      email : editEmail,
      sexo : editSexo,
      descripcion: editDescription
    }

    if(data != null){
     
      Object.values(data).map(value => {

        if(value === "" || value.toString().length === 0 || value === 0){
          dataComplete = false;
          setMessageType('error');
          setMessageContent('Los campos con (*) son obligatorios.');
        }else{
          dataComplete = true;
        }

      });

      if(dataComplete){
          fetch('http://localhost:8000/empleado/update',{
            method: 'POST',
            body: JSON.stringify(data),
            headers:{
              'Content-Type': 'application/json'
            }
          }).then(res => res.json())
          .catch(err => {
            setMessageType('error');
            setMessageContent(err);
          })
          .then(response => {
            if(response){
              setMessageType(response.title.toLowerCase());
              setMessageContent(response.message);

              _getEmpleados();
              dismissPanel();

            }
          });

        setMessageType('success');
        setMessageContent('Campos completos.');
      }

    }else{
      setMessageType('error');
      setMessageContent('Los campos con (*) son obligatorios.');
    }
  }

  useEffect(() => {
    _getEmpleados();
  },[]);

  useEffect(() => {
    if(_selectionDetail !== undefined){
      setEditID(_selectionDetail.id);
      setEditName(_selectionDetail.name);
      setEditEmail(_selectionDetail.email);
      setEditSexo(_selectionDetail.sex);
      setEditDescription(_selectionDetail.description);
    }
  },[_selectionDetail]);

  return(
    
      <React.Fragment>
          <div className="wrap">
              <div className="container">

                <Text block nowrap variant="xxLarge" style={{textAlign: 'center', marginTop: '3rem'}}>Crear Empleado.</Text>

                <Stack tokens={stackTokens} style={{marginTop: '3rem'}}>

                  <MessageBar messageBarType={messageType === 'warning' ? MessageBarType.warning : messageType === 'success' ? MessageBarType.success : MessageBarType.error}>
                    {messageContent}
                  </MessageBar>

                </Stack>

                <Stack horizontal tokens={stackTokens} styles={stackStyles}>

                  <Stack {...columnProps}>
                    <TextField label="Nombre Completo" required value={name} onChange={_onChangeName}/>
                    <ChoiceGroup options={options} label="Sexo" required={true} onChange={_onChangeSexo}/>
                    <TextField label="Descripción" multiline rows={3} required value={description} onChange={_onChangeDescription}/>
                  </Stack>

                  <Stack {...columnProps}>
                    <TextField label="Correo electrónico" required value={email} onChange={_onChangeEmail}/>
                    <Dropdown
                      placeholder={selectedItemArea ? selectedItemArea.text : 'Seleccione una opción'}
                      onChange={_onChangeArea}
                      selectedKey={selectedItemArea ? selectedItemArea.key : undefined}
                      label="Area"
                      options={DropDownAreaList}
                      styles={dropdownStyles}
                      required
                    />
                    <Checkbox label="Deseo recibir boletín informativo." onChange={_onChangeBoletin} />
                    <Dropdown
                      multiSelect
                      placeholder="Seleccione una opción"
                      onChange={_onChangeRoles}
                      label="Role"
                      options={DropDownRoleList}
                      styles={dropdownStyles}
                      required
                    />

                  </Stack>

                </Stack>

                <PrimaryButton text="Guardar" onClick={_saveEmpleado} allowDisabledFocus style={{marginTop: '2rem'}}/>

                  <Stack horizontal styles={stackManagementButtonStyles}>
                    <Text style={
                        {
                          float: 'left', 
                          width: 'calc(100% - 180px)', 
                          height: 44, display: 'flex', 
                          alignItems: 'center', 
                          marginLeft: '2rem'
                        }
                      }
                      nowrap variant="xLarge"
                    >
                      Empleados
                    </Text>
                    <Stack style={{height: 44, width: 180, display: 'flex', flexDirection: 'row', marginRight: '2rem'}}>
                      {_selectionDetail !== undefined ? <CommandBarButton iconProps={updateteIcon} text="Actualizar" onClick={openPanel}/> : ''}
                      {_selectionDetail !== undefined ? <CommandBarButton iconProps={deleteIcon} text="Eliminar" id={buttonId} onClick={toggleIsCalloutVisible}/> : ''}
                    </Stack>
                  </Stack>

                  <DetailsList
                    items={_items}
                    columns={_columns}
                    selection={_selection}
                    selectionMode={SelectionMode.single}
                    selectionPreservedOnEmptyClick={true}
                  />

                  <Panel
                    headerText="Actualizar usuario"
                    isOpen={isOpen}
                    onDismiss={dismissPanel}
                    closeButtonAriaLabel="Close"
                  >
                    <Stack tokens={stackTokens} styles={stackPanelStyles}>

                      <Stack {...columnPanelProps}>
                        <TextField label="Nombre Completo" required value={editName} onChange={_onChangeEditName}/>
                        <TextField label="Correo electrónico" required value={editEmail} onChange={_onChangeEditEmail}/>
                        <ChoiceGroup options={options} selectedKey={editSexo} label="Sexo" required={true} onChange={_onChangeEditSexo}/>
                        <TextField label="Descripción" multiline rows={3} required value={editDescription} onChange={_onChangeEditDescription}/>
                      </Stack>

                      <Stack {...columnPanelProps}>
                        <PrimaryButton text="Actualizar" onClick={_updateEmpleado} allowDisabledFocus style={{marginTop: '2rem'}}/>
                      </Stack>

                    </Stack>

                  </Panel>
                  
                  {isCalloutVisible ? (
                    <FocusTrapCallout
                      role="alertdialog"
                      className={styles.callout}
                      gapSpace={0}
                      target={`#${buttonId}`}
                      onDismiss={toggleIsCalloutVisible}
                      setInitialFocus
                    >
                      <Text block variant="xLarge" className={styles.title}>
                        Eliminar registro
                      </Text>
                      <Text block variant="small">
                        Estás seguro de que deseas eliminar el registro seleccionado.
                      </Text>

                      <FocusZone handleTabKey={FocusZoneTabbableElements.all} isCircularNavigation>
                        <Stack className={styles.buttons} gap={8} horizontal>
                          <PrimaryButton onClick={toggleIsCalloutVisible}>Si</PrimaryButton>
                          <DefaultButton onClick={toggleIsCalloutVisible}>No</DefaultButton>
                        </Stack>
                      </FocusZone>
                    </FocusTrapCallout>
                  ) 
                : null}

                  <Stack style={{marginTop: '3rem', display: 'flex', justifyContent: 'center', alignItems: 'center', height: 100}}>
                      <Text>Developed with <strong>React, Symfony & Microsoft Fluent UI</strong> By <strong>Brayan Alexis Angulo R.</strong></Text>
                  </Stack>

              </div>
          </div>
      </React.Fragment>
  )
}

export {App}